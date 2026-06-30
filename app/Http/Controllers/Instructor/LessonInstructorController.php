<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GereParInstructeur;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonInstructorController extends Controller
{
    use GereParInstructeur;


    // private function assertOwnsCours(Course $cours): void
    // {
    //     abort_unless((int) $cours->instructor_id === (int) auth()->id(), 403);
    // }

    // private function assertOwnsLecon(Lesson $lesson): void
    // {
    //     abort_unless((int) $lesson->instructor_id === (int) auth()->id(), 403);
    // }

    // Remplace $cours par $cour
    public function index($id)
    {
        $cours = Course::where('id', $id)
            ->where('instructor_id', auth()->id())
            ->firstOrFail();

        $lecons = $cours->lecons()
            ->ordonnees()
            ->get();

        return view('instructor.lessons.index', compact('cours', 'lecons'));
    }

    public function create(Course $cour)
    {
        //$this->assertOwnsCours($cour);

        return view('instructor.lessons.create', [
            'cours' => $cour,
            'cour'  => $cour,
        ]);
    }

    public function store(Request $request, Course $cours)
    {
        // $this->assertOwnsCours($cours);

        // Validation standard
        $validated = $request->validate($this->reglesLecon());

        // Construction des données
        $data = $this->construireDataLecon($request, $validated, $cours, null, auth()->id());

        $lesson = Lesson::create($data);

        // Retourne JSON si l'appel vient de Flutter, sinon redirige (Web)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['success' => true, 'lecon' => $lesson], 201);
        }

        return redirect()->route('instructor.cours.lessons.index', $cours)
            ->with('success', 'Leçon créée avec succès.');
    }

    public function edit(Course $cours, Lesson $lesson)
    {
        // $this->assertOwnsCours($cours);
        // $this->assertOwnsLecon($lesson);
        abort_unless($lesson->cours_id === $cours->id, 404);

        return view('instructeur.lessons.edit', [
            'cours'  => $cours,
            'cour'   => $cours,
            'lesson' => $lesson,
        ]);
    }

    public function update(Request $request, Course $cours, Lesson $lesson)
    {
        // $this->assertOwnsCours($cours);
        // $this->assertOwnsLecon($lesson);
        abort_unless($lesson->cours_id === $cours->id, 404);

        $validated = $request->validate($this->reglesLecon());
        $data = $this->construireDataLecon($request, $validated, $cours, $lesson, auth()->id());

        $data['cours_id']       = $cours->id;
        $data['instructor_id'] = auth()->id();

        $lesson->update($data);

        return redirect()->route('instructeur.cours.lessons.index', $cours)
            ->with('success', 'Leçon mise à jour.');
    }

    public function destroy(Course $cours, Lesson $lesson)
    {
        // $this->assertOwnsCours($cours);
        // $this->assertOwnsLecon($lesson);

        if ($lesson->fichier_audio) {
            Storage::disk('public')->delete($lesson->fichier_audio);
        }
        $lesson->delete();

        return redirect()->route('instructeur.cours.lessons.index', $cours)
            ->with('success', 'Leçon supprimée.');
    }

    public function reordonner(Request $request, Course $cours)
    {
        // $this->assertOwnsCours($cours);
        $request->validate(['ordre' => 'required|array', 'ordre.*' => 'integer']);

        foreach ($request->ordre as $position => $lessonId) {
            Lesson::where('id', $lessonId)
                  ->where('cours_id', $cours->id)
                  ->where('instructor_id', auth()->id()) // ✅ sécurité supplémentaire
                  ->update(['ordre' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GereParInstructeur;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonAdminController extends Controller
{
    use GereParInstructeur;


    public function index(Course $cour)
    {
       
        $lecons       = $cour->lecons()->with('instructor')->ordonnees()->get();
        $instructeurs = User::role('instructor')->orderBy('first_name')->get(['id', 'first_name']);

        return view('admin.lessons.index', compact('cour', 'lecons', 'instructeurs'));
    }

    public function create(Course $cour)
    {

        $instructeurs = User::role('instructor')->orderBy('first_name')->get(['id', 'first_name']);

        return view('admin.lessons.create', [
            'cours'        => $cour,
            'cour'         => $cour,   // alias pour les vues qui utilisent $cour
            'instructeurs' => $instructeurs,
        ]);
    }

    public function store(Request $request, Course $cour)
    {
        // Vérification explicite — si le route model binding échoue silencieusement
        // abort_if(! $cours->id, 404, 'Cours introuvable.');

        $validated = $request->validate(array_merge($this->reglesLecon(), [
            'instructor_id' => 'nullable|exists:users,id',   // ✅ instructor (en) pas instructeur (fr)
        ]));

        $instructorId = $validated['instructor_id'] ?? auth()->id();

        $data = $this->construireDataLecon($request, $validated, $cour, null, $instructorId);

        // Sécurité : on force cours_id explicitement
        $data['cours_id'] = $cour->id;

        Lesson::create($data);

        return redirect()
            ->route('admin.cours.lessons.index', $cour)
            ->with('success', 'Leçon créée avec succès.');
    }

    public function edit(Course $cour, Lesson $lesson)
    {
        abort_unless($lesson->cours_id === $cour->id, 404);

        $instructeurs = User::role('instructor')->orderBy('first_name')->get(['id', 'first_name']);

        return view('admin.lessons.edit', [
            'cours'        => $cour,
            'cour'         => $cour,
            'lesson'       => $lesson,
            'instructeurs' => $instructeurs,
        ]);
    }

    public function update(Request $request, Course $cour, Lesson $lesson)
    {
        abort_unless($lesson->cours_id === $cour->id, 404);

        $validated = $request->validate(array_merge($this->reglesLecon(), [
            'instructor_id' => 'nullable|exists:users,id',
        ]));

        $instructorId = $validated['instructor_id']
            ?? $lesson->instructor_id
            ?? auth()->id();

        $data            = $this->construireDataLecon($request, $validated, $cour, $lesson, $instructorId);
        $data['cours_id'] = $cour->id;   // sécurité

        $lesson->update($data);

        return redirect()
            ->route('admin.cours.lessons.index', $cour)
            ->with('success', 'Leçon mise à jour.');
    }

    public function destroy(Course $cours, Lesson $lesson)
    {
        abort_unless($lesson->cours_id === $cours->id, 404);

        if ($lesson->fichier_audio) {
            Storage::disk('public')->delete($lesson->fichier_audio);
        }

        $lesson->delete();

        return redirect()
            ->route('admin.cours.lessons.index', $cours)
            ->with('success', 'Leçon supprimée.');
    }

    public function reordonner(Request $request, Course $cours)
    {
        $request->validate(['ordre' => 'required|array', 'ordre.*' => 'integer']);

        foreach ($request->ordre as $position => $lessonId) {
            Lesson::where('id', $lessonId)
                  ->where('cours_id', $cours->id)
                  ->update(['ordre' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}
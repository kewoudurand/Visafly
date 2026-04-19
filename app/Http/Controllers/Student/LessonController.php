<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GereParInstructeur;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    use GereParInstructeur;
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super-admin']);
    }

    // ── Index ─────────────────────────────────────────────────────

    public function index(Course $cours)
    {
        $lecons = $cours->lessons()
            ->with('instructor')   // eager load pour afficher le nom dans la vue
            ->ordonnees()
            ->get();

        // Liste des instructeurs pour le filtre éventuel dans la vue
        $instructors = User::role('instructor')->orderBy('first_name')->get(['id', 'first_name']);

        return view('instructor.lessons.index', compact('cours', 'lecons', 'instructors'));
    }

    // ── Create / Store ───────────────────────────────────────────

    public function create(Course $cours)
    {
        $instructors = User::role('instructor')->orderBy('first_name')->get(['id', 'first_name']);

        return view('instructor.lessons.create', compact('cours', 'instructors'));
    }

    public function store(Request $request, Course $cours)
    {
        $validated = $request->validate(array_merge($this->reglesLecon(), [
            // L'admin peut choisir l'instructeur ; par défaut lui-même
            'instructeur_id' => 'nullable|exists:users,id',
        ]));

        // Si aucun instructeur choisi, l'admin lui-même est auteur
        $instructeurId = $validated['instructeur_id'] ?? auth()->id();

        $data = $this->construireDataLecon($request, $validated, $cours, null, $instructeurId);

        Lesson::create($data);

        return redirect()
            ->route('instructor.courses.index', $cours)
            ->with('success', 'Leçon créée avec succès.');
    }

    // ── Edit / Update ────────────────────────────────────────────

    public function edit(Course $cours, Lesson $lesson)
    {
        abort_unless($lesson->cours_id === $cours->id, 404);

        $instructeurs = User::role('instructeur')->orderBy('first_name')->get(['id', 'first_name']);

        return view('instructor.lessons.edit', compact('cours', 'lesson', 'instructeurs'));
    }

    public function update(Request $request, Course $cours, Lesson $lesson)
    {
        abort_unless($lesson->cours_id === $cours->id, 404);

        $validated = $request->validate(array_merge($this->reglesLecon(), [
            'instructeur_id' => 'nullable|exists:users,id',
        ]));

        $instructeurId = $validated['instructeur_id'] ?? $lesson->instructeur_id ?? auth()->id();

        $data = $this->construireDataLecon($request, $validated, $cours, $lesson, $instructeurId);

        $lesson->update($data);

        return redirect()
            ->route('instructor.lessons.index', $cours)
            ->with('success', 'Leçon mise à jour.');
    }

    // ── Destroy ───────────────────────────────────────────────────

    public function destroy(Course $cours, Lesson $lesson)
    {
        abort_unless($lesson->cours_id === $cours->id, 404);

        if ($lesson->fichier_audio) {
            Storage::disk('public')->delete($lesson->fichier_audio);
        }

        $lesson->delete();

        return redirect()
            ->route('instructor.courses.index', $cours)
            ->with('success', 'Leçon supprimée.');
    }

    // ── Réordonner (AJAX) ─────────────────────────────────────────

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
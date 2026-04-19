<?php

namespace App\Http\Controllers\Instructeur;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GereParInstructeur;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Instructeur\LessonController
 *
 * L'instructeur :
 *   - Ne peut voir les cours qu'il possède (instructeur_id = lui)
 *   - Ne peut créer des leçons que dans SES cours
 *   - Ne peut modifier/supprimer que SES leçons
 *
 * Double vérification : middleware role + peutEtreGerePar() sur le modèle.
 */
class LessonController extends Controller
{
    use GereParInstructeur;

    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor']);
    }

    // ── Index ─────────────────────────────────────────────────────

    public function index(Course $cours)
    {
        // L'instructeur ne peut accéder qu'à ses propres cours
        abort_unless($cours->peutEtreGerePar(), 403);

        $lecons = $cours->lessons()
            ->deInstructeur(auth()->id())   // voit uniquement ses leçons dans ce cours
            ->ordonnees()
            ->get();

        return view('instructor.lessons.index', compact('cours', 'lecons'));
    }

    // ── Create / Store ───────────────────────────────────────────

    public function create(Course $cours)
    {
        abort_unless($cours->peutEtreGerePar(), 403);

        return view('instructor.lessons.create', compact('cours'));
    }

    public function store(Request $request, Course $cours)
    {
        abort_unless($cours->peutEtreGerePar(), 403);

        $validated = $request->validate($this->reglesLecon());

        // instructeur_id = toujours l'utilisateur connecté, non modifiable
        $data = $this->construireDataLecon($request, $validated, $cours, null, auth()->id());

        Lesson::create($data);

        return redirect()
            ->route('instructor.courses.index', $cours)
            ->with('success', 'Leçon créée avec succès.');
    }

    // ── Edit / Update ────────────────────────────────────────────

    public function edit(Course $cours, Lesson $lesson)
    {
        abort_unless($cours->peutEtreGerePar(), 403);
        abort_unless($lesson->cours_id === $cours->id, 404);
        abort_unless($lesson->peutEtreGereePar(), 403);   // vérification sur la leçon elle-même

        return view('instructor.lessons.edit', compact('cours', 'lesson'));
    }

    public function update(Request $request, Course $cours, Lesson $lesson)
    {
        abort_unless($cours->peutEtreGerePar(), 403);
        abort_unless($lesson->cours_id === $cours->id, 404);
        abort_unless($lesson->peutEtreGereePar(), 403);

        $validated = $request->validate($this->reglesLecon());

        // instructeur_id reste celui de la leçon, l'instructeur ne peut pas le changer
        $data = $this->construireDataLecon($request, $validated, $cours, $lesson, $lesson->instructeur_id ?? auth()->id());

        $lesson->update($data);

        return redirect()
            ->route('instructor.courses.index', $cours)
            ->with('success', 'Leçon mise à jour.');
    }

    // ── Destroy ───────────────────────────────────────────────────

    public function destroy(Course $cours, Lesson $lesson)
    {
        abort_unless($cours->peutEtreGerePar(), 403);
        abort_unless($lesson->cours_id === $cours->id, 404);
        abort_unless($lesson->peutEtreGereePar(), 403);

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
        abort_unless($cours->peutEtreGerePar(), 403);
        $request->validate(['ordre' => 'required|array', 'ordre.*' => 'integer']);

        foreach ($request->ordre as $position => $lessonId) {
            // On s'assure que la leçon appartient bien à cet instructeur
            Lesson::where('id', $lessonId)
                  ->where('cours_id', $cours->id)
                  ->where('instructeur_id', auth()->id())
                  ->update(['ordre' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}
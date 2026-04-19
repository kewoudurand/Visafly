<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GereParInstructeur;
use App\Models\Course;
use Illuminate\Http\Request;

/**
 * Instructeur\CourseController
 *
 * L'instructeur ne voit et ne gère QUE ses propres cours
 * (instructeur_id = auth()->id()).
 *
 * Chaque accès est doublement vérifié :
 *   1. Via le middleware role:instructeur
 *   2. Via peutEtreGerePar() sur l'instance du modèle
 */
class CourseController extends Controller
{
    use GereParInstructeur;

    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor']);
    }

    // ── Index : seulement MES cours ───────────────────────────────

    public function index()
    {
        $cours = Course::deInstructeur(auth()->id())
            ->withCount('lessons')
            ->orderBy('ordre')
            ->paginate(20);

        return view('instructor.courses.index', compact('cours'));
    }

    // ── Create / Store ───────────────────────────────────────────

    public function create()
    {
        return view('instructor.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->reglesCours());

        // instructeur_id = toujours l'utilisateur connecté, non modifiable
        Course::create($this->construireDataCours($request, $validated, auth()->id()));

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Cours créé avec succès.');
    }

    // ── Edit / Update ────────────────────────────────────────────

    public function edit(Course $cours)
    {
        abort_unless($cours->peutEtreGerePar(), 403);

        return view('instructor.courses.edit', compact('cours'));
    }

    public function update(Request $request, Course $cours)
    {
        abort_unless($cours->peutEtreGerePar(), 403);

        $validated = $request->validate($this->reglesCours());

        // L'instructeur ne peut pas changer l'instructeur_id
        $cours->update($this->construireDataCours($request, $validated, auth()->id(), $cours));

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Cours mis à jour.');
    }

    // ── Destroy ───────────────────────────────────────────────────

    public function destroy(Course $cours)
    {
        abort_unless($cours->peutEtreGerePar(), 403);

        $cours->delete();

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Cours supprimé.');
    }
}
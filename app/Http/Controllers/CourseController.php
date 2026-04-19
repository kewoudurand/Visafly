<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Http\Controllers\Concerns\GereParInstructeur;
use Illuminate\Http\Request;

class CourseController extends Controller
{
     use GereParInstructeur;
    // ── Liste publique des cours ──────────────────────────────────
    public function choose(Request $request)
    {
        $cours = Course::with('instructor')
            ->withCount(['lecons' => fn($q) => $q->where('publiee', true)])
            ->where('publie', true)
            ->orderBy('ordre')
            ->when($request->filled('niveau'), fn($q) => $q->where('niveau', strtoupper($request->niveau)))
            ->get();

        return view('courses.list', compact('cours'));
    }

    // ── Afficher la liste des cours (admin) ───────────────────────
    public function index()
    {
        $cours = Course::with('instructor')
            ->orderBy('ordre')
            ->paginate(20);
 
        $instructeurs = User::role('instructor')->orderBy('first_name')->get(['id', 'first_name']);
 
        return view('admin.courses.index', compact('cours', 'instructeurs'));
    }

    public function create()
    {
        $instructeurs = User::role('instructor')->orderBy('first_name')->get(['id', 'first_name']);
 
        return view('admin.courses.create', compact('instructeurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(array_merge($this->reglesCours(), [
            'instructor_id' => 'nullable|exists:users,id',
        ]));
 
        $instructeurId = $validated['instructor_id'] ?? auth()->id();
 
        Course::create($this->construireDataCours($request, $validated, $instructeurId));
 
        return redirect()->route('admin.cours.index')->with('success', 'Cours créé.');
    }
 
    public function edit(Course $cours)
    {
        $instructeurs = User::role('instructeur')->orderBy('name')->get(['id', 'name']);
 
        return view('admin.courses.edit', compact('cours', 'instructeurs'));
    }
 
    public function update(Request $request, Course $cours)
    {
        $validated = $request->validate(array_merge($this->reglesCours(), [
            'instructeur_id' => 'nullable|exists:users,id',
        ]));
 
        $instructeurId = $validated['instructeur_id'] ?? $cours->instructeur_id ?? auth()->id();
 
        $cours->update($this->construireDataCours($request, $validated, $instructeurId, $cours));
 
        return redirect()->route('admin.cours.index')->with('success', 'Cours mis à jour.');
    }
 
    public function destroy(Course $cours)
    {
        $cours->delete();
 
        return redirect()->route('admin.cours.index')->with('success', 'Cours supprimé.');
    }

    // ── Afficher un cours avec toutes ses leçons ──────────────────
    public function show(Course $cours)
    {
        if (! $cours->publie && ! auth()->user()?->hasAnyRole(['admin', 'super-admin'])) {
            abort(404);
        }

        $lecons = $cours->lecons()
            ->where('publiee', true)
            ->orderBy('ordre')
            ->get();

        // Progression utilisateur [lesson_id => statut]
        $progressions = [];
        if (auth()->check()) {
            $progressions = \App\Models\LessonProgression::where('user_id', auth()->id())
                ->whereIn('lesson_id', $lecons->pluck('id'))
                ->pluck('statut', 'lesson_id')
                ->toArray();
        }

        $totalLecons    = $lecons->count();
        $leconsTerminees = count(array_filter($progressions, fn($s) => $s === 'terminee'));
        $pourcentage    = $totalLecons > 0 ? intval(($leconsTerminees / $totalLecons) * 100) : 0;

        return view('courses.show', compact('cours', 'lecons', 'progressions', 'pourcentage'));
    }

    // ── Afficher une leçon ────────────────────────────────────────
    public function lecon(Course $cours, Lesson $lecon)
    {
        abort_unless($lecon->cours_id === $cours->id && $lecon->publiee, 404);

        $precedente = Lesson::where('cours_id', $cours->id)
            ->where('publiee', true)
            ->where('ordre', '<', $lecon->ordre)
            ->orderBy('ordre', 'desc')->first();

        $suivante = Lesson::where('cours_id', $cours->id)
            ->where('publiee', true)
            ->where('ordre', '>', $lecon->ordre)
            ->orderBy('ordre')->first();

        return view('courses.lesson', compact('cours', 'lecon', 'precedente', 'suivante'));
    }

    // ── Valider les exercices (POST AJAX) ─────────────────────────
    public function valider(Request $request, Lesson $lecon)
    {
        $cours     = $lecon->cours;
        $exercices = $lecon->exercices ?? [];
        $reponses  = $request->reponses ?? [];
        $bonnes    = 0;
        $total     = count($exercices);

        foreach ($exercices as $idx => $ex) {
            $rep     = mb_strtolower(trim($reponses[$idx] ?? ''));
            $correct = mb_strtolower(trim($ex['reponse'] ?? ''));
            if ($rep === $correct) $bonnes++;
        }

        $score   = $total > 0 ? intval(($bonnes / $total) * 100) : 100;
        $termine = $score >= 60;
        $points  = $termine ? $lecon->points_recompense : 0;

        if (auth()->check()) {
            \App\Models\LessonProgression::updateOrCreate(
                ['user_id' => auth()->id(), 'lesson_id' => $lecon->id],
                [
                    'cours_id'        => $cours->id,
                    'statut'          => $termine ? 'terminee' : 'en_cours',
                    'score'           => $score,
                    'bonnes_reponses' => $bonnes,
                    'total_questions' => $total,
                    'points_gagnes'   => $points,
                    'terminee_le'     => $termine ? now() : null,
                ]
            );

            if ($termine) {
                \App\Models\CourseProgression::mettreAJour(auth()->id(), $cours->id, $points);
            }
        }

        return response()->json([
            'success'       => true,
            'score'         => $score,
            'bonnes'        => $bonnes,
            'total'         => $total,
            'points_gagnes' => $points,
            'termine'       => $termine,
        ]);
    }
}
<?php
// app/Http/Controllers/CoursAllemandController.php (WEB - Blade)

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Services\CoursAllemandService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursAllemandController extends Controller
{
    public function __construct(private CoursAllemandService $service) {}

    // Liste des cours
    public function index()
    {
        $cours = $this->service->listeCoursAvecProgression(Auth::id());
        $stats = Auth::check()
            ? $this->service->statsUtilisateur(Auth::id())
            : null;

        return view('courses.index', compact('cours', 'stats'));
    }

    // Détail d'un cours (ses leçons)
    public function show(string $slug)
    {
        $cours = Course::where('slug', $slug)
            ->where('actif', true)
            ->with('lecons')
            ->firstOrFail();

        // IDs des leçons terminées par l'utilisateur
        $leconsTerminees = [];
        if (Auth::check()) {
            $leconsTerminees = $cours->lecons
                ->filter(fn($l) => $l->estTermineePar(Auth::id()))
                ->pluck('id')->toArray();
        }

        $progression = Auth::check()
            ? $cours->progressionPour(Auth::id())
            : 0;

        return view('lessons.show', compact('cours', 'leconsTerminees', 'progression'));
    }

    // Afficher une leçon
    public function lecon(string $coursSlug, string $leconSlug)
    {
        $cours = Course::where('slug', $coursSlug)->firstOrFail();
        $lecon = Lesson::where('slug', $leconSlug)
            ->where('cours_id', $cours->id)
            ->firstOrFail();

        // Vérifier accès (premium)
        if (!$lecon->gratuite && !Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Connectez-vous pour accéder à cette leçon.');
        }

        if (Auth::check()) {
            $this->service->commencerLecon($lecon, Auth::id());
        }

        // Navigation : leçon précédente / suivante
        $precedente = Lesson::where('cours_id', $cours->id)
            ->where('ordre', '<', $lecon->ordre)
            ->orderByDesc('ordre')->first();

        $suivante = Lesson::where('cours_id', $cours->id)
            ->where('ordre', '>', $lecon->ordre)
            ->orderBy('ordre')->first();

        return view('courses.lesson', compact('cours', 'lecon', 'precedente', 'suivante'));
    }

    // Valider les exercices d'une leçon
    public function valider(Request $request, int $leconId)
    {
        $lecon = Lesson::findOrFail($leconId);

        $request->validate([
            'reponses' => 'required|array',
        ]);

        // Calculer le score
        $exercices = $lecon->exercices ?? [];
        $score     = $this->calculerScore($exercices, $request->reponses);

        $progres = $this->service->validerLecon(
            $lecon,
            Auth::id(),
            $score,
            $request->reponses
        );

        return response()->json([
            'success'      => true,
            'score'        => $score,
            'points_gagnes'=> $progres->points_gagnes,
            'message'      => $score >= 70
                ? '🎉 Félicitations ! Leçon validée !'
                : '📚 Continuez à pratiquer !',
        ]);
    }

    private function calculerScore(array $exercices, array $reponses): int
    {
        if (empty($exercices)) return 100;

        $total   = count($exercices);
        $correct = 0;

        foreach ($exercices as $idx => $ex) {
            $rep = $reponses[$idx] ?? null;
            if ($rep !== null && strtolower(trim((string)$rep)) === strtolower(trim((string)$ex['reponse']))) {
                $correct++;
            }
        }

        return $total > 0 ? (int) round(($correct / $total) * 100) : 0;
    }

    public function choose()
    {
        return view('courses.list');
    }
}
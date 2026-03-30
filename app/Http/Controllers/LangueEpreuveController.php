<?php
// ═══════════════════════════════════════════════════════════════
//  app/Http/Controllers/LangueEpreuveController.php
// ═══════════════════════════════════════════════════════════════
namespace App\Http\Controllers;

use App\Models\Langue;
use App\Models\LangueDiscipline;
use App\Models\LangueSerie;
use App\Models\LangueQuestion;
use App\Models\TcfAbonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LangueEpreuveController extends Controller
{
    // ═══════════════════════════════════════
    //  Page d'accueil — 4 cartes examens
    // ═══════════════════════════════════════
    public function index()
    {
        
        return view('langues.series');
    }

    // ═══════════════════════════════════════
    //  Liste des séries d'un examen
    // ═══════════════════════════════════════
    public function series(string $code)
    {
        $langue = Langue::where('code', $code)->where('actif', true)->firstOrFail();

        $series = LangueSerie::whereIn(
            'discipline_id',
            $langue->disciplines()->pluck('id')
        )->where('active', true)->orderBy('ordre')->get();

        // Vérifie si l'utilisateur a un abonnement actif
        $aAbonnement = false;
        if (Auth::check()) {
            $aAbonnement = TcfAbonnement::where('user_id', Auth::id())
                ->where('actif', true)
                ->where('fin_at', '>=', now())
                ->exists();
        }

        return view('langues.series', compact('langue', 'series', 'aAbonnement'));
    }

    // ═══════════════════════════════════════
    //  Page choix discipline + modal confirmation
    // ═══════════════════════════════════════
    public function disciplines(string $code, LangueSerie $serie)
    {
        $langue = Langue::where('code', $code)->firstOrFail();

        // Vérifier accès à la série
        if (!$serie->gratuite && Auth::check()) {
            $aAbonnement = TcfAbonnement::where('user_id', Auth::id())
                ->where('actif', true)->where('fin_at', '>=', now())->exists();
            if (!$aAbonnement) {
                return redirect()->route('tcf.abonnement')
                    ->with('error', 'Cette série nécessite un abonnement.');
            }
        }

        $disciplines = $langue->disciplines()
            ->where('actif', true)->orderBy('ordre')->get();

        return view('langues.disciplines', compact('langue', 'serie', 'disciplines'));
    }

    // ═══════════════════════════════════════
    //  Interface d'épreuve
    // ═══════════════════════════════════════
    public function epreuve(string $code, LangueSerie $serie, LangueDiscipline $discipline)
    {
        $langue = Langue::where('code', $code)->firstOrFail();

        $questions = LangueQuestion::where('serie_id', $serie->id)
            ->with(['reponses' => fn($q) => $q->orderBy('ordre')])
            ->orderBy('ordre')
            ->get();

        abort_if($questions->isEmpty(), 404, 'Cette série n\'a pas encore de questions.');

        // Enregistrer l'heure de début en session
        Session::put("epreuve_debut_{$serie->id}_{$discipline->id}", now()->timestamp);

        return view('langues.epreuve',
            compact('langue', 'serie', 'discipline', 'questions'));
    }

    // ═══════════════════════════════════════
    //  Soumettre et calculer les résultats
    // ═══════════════════════════════════════
    public function soumettre(Request $request, string $code, LangueSerie $serie, LangueDiscipline $discipline)
    {
        $langue    = Langue::where('code', $code)->firstOrFail();
        $reponses  = $request->input('reponses', []); // [question_id => reponse_id]

        $questions = LangueQuestion::where('serie_id', $serie->id)
            ->with('reponses')
            ->orderBy('ordre')
            ->get();

        // ── Calcul résultats ──
        $bonnes       = 0;
        $mauvaises    = 0;
        $nonRepondues = 0;
        $pointsTotal  = 0;
        $pointsObtenus= 0;
        $corrections  = [];

        foreach ($questions as $q) {
            $pointsTotal += $q->points;
            $reponduId    = $reponses[$q->id] ?? null;
            $bonneRep     = $q->reponses->firstWhere('correcte', true);
            $estCorrecte  = $reponduId && $bonneRep && $reponduId == $bonneRep->id;

            if (!$reponduId) {
                $nonRepondues++;
            } elseif ($estCorrecte) {
                $bonnes++;
                $pointsObtenus += $q->points;
            } else {
                $mauvaises++;
            }

            $corrections[] = [
                'question' => $q,
                'reponses' => $q->reponses,
                'repondue' => $reponduId,
                'correct'  => $estCorrecte,
            ];
        }

        $totalQuestions = $questions->count();
        $score = $totalQuestions > 0
            ? (int) round(($bonnes / $totalQuestions) * 100)
            : 0;

        // Temps passé
        $debutKey   = "epreuve_debut_{$serie->id}_{$discipline->id}";
        $debut      = Session::get($debutKey, now()->timestamp);
        $tempsPasse = (int) round((now()->timestamp - $debut) / 60);
        Session::forget($debutKey);

        // ── Enregistrer en session pour le student dashboard ──
        if (Auth::check()) {
            // Si tu as un modèle TcfPassage ou LanguePassage — adapter ici
            // Pour l'instant on stocke en session pour la vue résultats
        }

        return view('langues.resultat', compact(
            'langue', 'serie', 'discipline',
            'score', 'bonnes', 'mauvaises', 'nonRepondues',
            'totalQuestions', 'corrections', 'tempsPasse'
        ));
    }
}
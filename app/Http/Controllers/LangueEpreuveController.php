<?php

namespace App\Http\Controllers;

use App\Models\Langue;
use App\Models\LangueDiscipline;
use App\Models\LangueSerie;
use App\Models\LangueQuestion;
use App\Models\LanguePassage;
use App\Models\LanguePassageReponse;
use App\Models\LangueAbonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LangueEpreuveController extends Controller
{
    // ── Page accueil 4 examens ──
    public function index()
    {
        $series = LangueSerie::where('active', true)->orderBy('ordre')->get();
        $langues = Langue::where('actif', true)->orderBy('ordre')->get();
        $disciplines = LangueDiscipline::whereIn('id', $langues->pluck('disciplines.*.id')->flatten())->get();
        
        return view('langues.disciplines', [
            'langues' => Langue::where('actif', true)->orderBy('ordre')->get(),
            'series' => $series,
            'serie' => $series->first(), // Passer aussi le premier pour le title
            'langue' => $langues->first(), // Passer aussi la première langue pour le title
            'disciplines' => $disciplines,
        ]);
    }

    // ── Liste des séries ──
    public function series(string $code)
    {
        $langue = Langue::where('code', $code)->where('actif', true)->firstOrFail();

        $series = LangueSerie::whereIn(
            'discipline_id',
            $langue->disciplines()->pluck('id')
        )->where('active', true)->orderBy('ordre')->get();

        $aAbonnement = $this->verifierAbonnement();

        return view('langues.series', compact('langue', 'series', 'aAbonnement'));
    }

    // ── Choix discipline + modal ──
    public function disciplines(string $code, LangueSerie $serie)
    {
        $langue = Langue::where('code', $code)->firstOrFail();

        if (!$serie->gratuite && Auth::check()) {
            if (!$this->verifierAbonnement()) {
                return redirect()->route('abonnement.index')
                    ->with('error', 'Cette série nécessite un abonnement.');
            }
        }

        $disciplines = $langue->disciplines()
            ->where('actif', true)->orderBy('ordre')->get();

        return view('langues.disciplines', compact('langue', 'serie', 'disciplines'));
    }

    // ── Interface épreuve ──
    public function epreuve(string $code, LangueSerie $serie, LangueDiscipline $discipline)
    {
        $langue    = Langue::where('code', $code)->firstOrFail();
        $questions = LangueQuestion::where('serie_id', $serie->id)
            ->with(['reponses' => fn($q) => $q->orderBy('ordre')])
            ->orderBy('ordre')->get();

        abort_if($questions->isEmpty(), 404, 'Aucune question dans cette série.');

        // Créer un passage en base (statut en_cours)
        $passage = LanguePassage::create([
            'user_id'         => Auth::id(),
            'serie_id'        => $serie->id,
            'discipline_id'   => $discipline->id,
            'statut'          => 'en_cours',
            'total_questions' => $questions->count(),
            'debut_at'        => now(),
        ]);

        Session::put("passage_id_{$serie->id}_{$discipline->id}", $passage->id);

        return view('langues.epreuve',
            compact('langue', 'serie', 'discipline', 'questions', 'passage'));
    }

    // ── Soumettre et calculer ──
    public function soumettre(Request $request, string $code, LangueSerie $serie, LangueDiscipline $discipline)
    {
        $langue    = Langue::where('code', $code)->firstOrFail();
        $reponses  = $request->input('reponses', []); // [question_id => reponse_id]

        $questions = LangueQuestion::where('serie_id', $serie->id)
            ->with('reponses')->orderBy('ordre')->get();

        // ── Récupérer le passage ──
        $passageId  = Session::get("passage_id_{$serie->id}_{$discipline->id}");
        $passage    = $passageId ? LanguePassage::find($passageId) : null;

        // Temps passé
        $debut      = $passage?->debut_at ?? now();
        $dureeSecondes = (int) $debut->diffInSeconds(now());

        // ── Calcul résultats ──
        $bonnes       = 0;
        $mauvaises    = 0;
        $nonRepondues = 0;
        $pointsTotal  = 0;
        $pointsObt    = 0;
        $corrections  = [];

        foreach ($questions as $q) {
            $pointsTotal += $q->points;
            $reponduId    = $reponses[$q->id] ?? null;
            $bonneRep     = $q->reponses->firstWhere('correcte', true);
            $estCorrecte  = $reponduId && $bonneRep && $reponduId == $bonneRep->id;

            if (!$reponduId)        $nonRepondues++;
            elseif ($estCorrecte) { $bonnes++; $pointsObt += $q->points; }
            else                    $mauvaises++;

            $corrections[] = [
                'question' => $q,
                'reponses' => $q->reponses,
                'repondue' => $reponduId,
                'correct'  => $estCorrecte,
            ];

            // ── Enregistrer chaque réponse ──
            if ($passage) {
                LanguePassageReponse::updateOrCreate(
                    ['passage_id' => $passage->id, 'question_id' => $q->id],
                    ['reponse_id' => $reponduId, 'correcte' => $estCorrecte]
                );
            }
        }

        $total = $questions->count();
        $score = $total > 0 ? (int) round(($bonnes / $total) * 100) : 0;
        $tempsPasse = (int) round($dureeSecondes / 60);
        $totalQuestions = $total;

        // ── Mettre à jour le passage en base ──
        if ($passage) {
            $passage->update([
                'statut'            => 'termine',
                'score'             => $score,
                'bonnes_reponses'   => $bonnes,
                'mauvaises_reponses'=> $mauvaises,
                'non_repondues'     => $nonRepondues,
                'total_questions'   => $total,
                'points_obtenus'    => $pointsObt,
                'points_total'      => $pointsTotal,
                'fin_at'            => now(),
                'duree_secondes'    => $dureeSecondes,
            ]);
        }

        Session::forget("passage_id_{$serie->id}_{$discipline->id}");

        return view('langues.resultat', compact(
            'langue', 'serie', 'discipline',
            'score', 'bonnes', 'mauvaises', 'nonRepondues',
            'totalQuestions', 'corrections', 'tempsPasse', 'passage'
        ))->with('totalQuestions', $total);
    }

    // ── Vérifier abonnement actif ──
    private function verifierAbonnement(): bool
    {
        if (!Auth::check()) return false;
        return LangueAbonnement::where('user_id', Auth::id())
            ->where('actif', true)->where('fin_at', '>=', now())->exists();
    }
}
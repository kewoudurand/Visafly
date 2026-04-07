<?php

namespace App\Http\Controllers;

use App\Models\Langue;
use App\Models\LanguePassage;
use App\Models\LangueSerie;
use Illuminate\Support\Facades\Auth;

class StudentResultController extends Controller
{
    /**
     * Page listant tous les examens que l'étudiant a passés
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer tous les examens que cet utilisateur a passés
        $examensPassages = LanguePassage::where('user_id', $user->id)
            ->where('statut', 'termine')
            ->with(['langue:id,code,nom,couleur', 'serie:id,titre'])
            ->latest('created_at')
            ->get()
            ->groupBy('langue_id');

        // Statistiques par examen
        $statsParExamen = [];
        foreach ($examensPassages as $langueId => $passages) {
            $langue = $passages->first()->langue;
            $statsParExamen[$langue->id] = [
                'langue' => $langue,
                'nb_passages' => $passages->count(),
                'score_moyen' => (int) round($passages->avg('score') ?? 0),
                'meilleur_score' => $passages->max('score') ?? 0,
                'dernier_passage' => $passages->first(),
            ];
        }

        return view('student.results.index', compact('statsParExamen', 'examensPassages'));
    }

    /**
     * Détails des résultats pour un examen spécifique
     */
    public function showExam(Langue $langue)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a passé cet examen
        $passages = LanguePassage::where('user_id', $user->id)
            ->where('langue_id', $langue->id)
            ->where('statut', 'termine')
            ->with([
                'serie:id,nom,titre',
                'discipline:id,nom,code',
            ])
            ->latest('created_at')
            ->get();

        if ($passages->isEmpty()) {
            return redirect()->route('student.results.index')
                ->with('error', "Vous n'avez aucun passage pour l'examen {$langue->nom}");
        }

        // Prendre le meilleur passage (ou le plus récent)
        $meilleurPassage = $passages->sortByDesc('score')->first();

        // Regrouper les scores par discipline
        $scoreParDiscipline = $passages->map(function($p) {
            return [
                'discipline' => $p->discipline,
                'score' => $p->score,
                'date' => $p->created_at,
            ];
        })->groupBy('discipline.id');

        // Préparer les résultats
        $resultats = [];
        $disciplines = [];
        
        foreach ($passages as $passage) {
            if (!isset($disciplines[$passage->discipline->id])) {
                $disciplines[$passage->discipline->id] = $passage->discipline;
            }
            
            if (!isset($resultats[$passage->discipline->id])) {
                $resultats[$passage->discipline->id] = [
                    'discipline' => $passage->discipline,
                    'passages' => [],
                ];
            }
            
            $resultats[$passage->discipline->id]['passages'][] = [
                'score' => $passage->score,
                'serie' => $passage->serie,
                'date' => $passage->created_at,
                'bonnes_reponses' => $passage->bonnes_reponses ?? 0,
                'mauvaises_reponses' => $passage->mauvaises_reponses ?? 0,
                'non_repondues' => $passage->non_repondues ?? 0,
                'total_questions' => $passage->total_questions ?? 0,
            ];
        }

        // Stats globales
        $stats = [
            'total_passages' => $passages->count(),
            'score_moyen' => (int) round($passages->avg('score') ?? 0),
            'meilleur_score' => $meilleurPassage->score,
            'pire_score' => $passages->min('score') ?? 0,
            'progression' => $this->calculerProgression($passages),
        ];

        return view('student.results.show', compact(
            'langue',
            'resultats',
            'stats',
            'meilleurPassage',
            'passages'
        ));
    }

    /**
     * Détails complets d'un passage spécifique
     */
    public function showPassage(LanguePassage $passage)
    {
        $user = Auth::user();
        
        // Vérifier que c'est le passage de l'utilisateur
        if ($passage->user_id !== $user->id) {
            abort(403, 'Non autorisé');
        }

        $passage->load([
            'langue:id,code,nom,couleur',
            'serie:id,nom,titre',
            'discipline:id,nom,code',
            'reponses:id,passage_id,question_id,reponse_donnee,correcte',
        ]);

        // Niveau atteint
        $niveau = $this->getNiveau($passage->score);

        return view('student.results.detail', compact('passage', 'niveau'));
    }

    /**
     * Calculer la progression entre passages
     */
    private function calculerProgression($passages)
    {
        if ($passages->count() < 2) {
            return null;
        }

        $passages = $passages->sortBy('created_at');
        $first = $passages->first();
        $last = $passages->last();
        
        $diff = $last->score - $first->score;
        $pourcentage = $first->score > 0 ? round(($diff / $first->score) * 100) : 0;

        return [
            'score_initial' => $first->score,
            'score_final' => $last->score,
            'difference' => $diff,
            'pourcentage' => $pourcentage,
            'positif' => $diff > 0,
        ];
    }

    /**
     * Obtenir le niveau CECR basé sur le score
     */
    private function getNiveau($score)
    {
        if ($score === null) return '—';
        
        return match (true) {
            $score >= 80 => 'C1-C2',
            $score >= 60 => 'B2',
            $score >= 50 => 'B1',
            $score >= 40 => 'A2',
            $score >= 20 => 'A1',
            default => 'A0',
        };
    }

    /**
     * Couleur du score
     */
    public function getCouleurScore($score)
    {
        return match (true) {
            $score >= 80 => '#1B3A6B',
            $score >= 60 => '#1cc88a',
            $score >= 40 => '#F5A623',
            default => '#E24B4A',
        };
    }
}
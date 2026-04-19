<?php

namespace App\Services;

use App\Models\CourseProgression;
use App\Models\LanguePassage;
use App\Models\Langue;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

// ─────────────────────────────────────────────────────────────────────────────
//  FICHIER : app/Services/CourseProgressionService.php
//
//  Architecture API-First :
//  Ce Service est le "cerveau" — il ne connaît ni Blade ni JSON.
//  Il retourne des données brutes (Models, Collections, arrays).
//  Les Controllers décident ensuite : view() ou response()->json().
//  Cela garantit que la même logique alimente Web ET Mobile sans duplication.
// ─────────────────────────────────────────────────────────────────────────────

class CourseProgressionService
{
    // ════════════════════════════════════════════════════════════
    //  DASHBOARD ÉTUDIANT
    //  Récupère tout ce qu'un étudiant a besoin de voir
    // ════════════════════════════════════════════════════════════

    public function getDashboardEtudiant(int $userId): array
    {
        // ── Stats globales (depuis langue_passages — existant) ──
        $passages = LanguePassage::where('user_id', $userId)
            ->with(['langue:id,code,nom,couleur', 'discipline:id,nom,code', 'serie:id,titre'])
            ->latest('created_at')
            ->get();

        $passagesTermines = $passages->where('statut', 'termine');

        // ── Progression par langue ──
        $progressionParLangue = $this->getProgressionParLangue($userId, $passages);

        // ── Activité récente (10 derniers passages) ──
        $activiteRecente = $passagesTermines->take(10);

        // ── Stats agrégées ──
        $stats = [
            'total_cours_commences' => $passages->unique(fn($p) => $p->serie_id.'-'.$p->discipline_id)->count(),
            'total_cours_termines'  => $passagesTermines->unique(fn($p) => $p->serie_id.'-'.$p->discipline_id)->count(),
            'score_moyen'           => (int) round($passagesTermines->avg('score') ?? 0),
            'temps_total_minutes'   => (int) round($passagesTermines->sum('duree_secondes') / 60),
            'serie_favorite'        => $this->getSerieFavorite($passagesTermines),
            'meilleur_score'        => (int) ($passagesTermines->max('score') ?? 0),
            'streak_jours'          => $this->calculerStreak($userId),
        ];

        // ── Recommandations (séries non tentées dans les langues actives) ──
        $recommandations = $this->getRecommandations($userId, $passages);

        return compact(
            'stats',
            'progressionParLangue',
            'activiteRecente',
            'recommandations'
        );
    }

    // ════════════════════════════════════════════════════════════
    //  DASHBOARD ADMIN — Vue d'ensemble de TOUS les étudiants
    // ════════════════════════════════════════════════════════════

    public function getDashboardAdmin(array $filtres = []): array
    {
        // ── Stats globales plateforme ──
        $statsGlobales = [
            'total_passages'    => LanguePassage::count(),
            'passages_termines' => LanguePassage::where('statut', 'termine')->count(),
            'score_moyen'       => (int) round(LanguePassage::where('statut','termine')->avg('score') ?? 0),
            'users_actifs'      => LanguePassage::where('created_at', '>=', now()->subDays(7))
                                                ->distinct('user_id')->count('user_id'),
        ];

        // ── Top étudiants ──
        $topEtudiants = LanguePassage::select(
                'user_id',
                DB::raw('COUNT(*) as nb_passages'),
                DB::raw('ROUND(AVG(score), 0) as score_moyen'),
                DB::raw('SUM(duree_secondes) as temps_total'),
                DB::raw('MAX(created_at) as derniere_activite')
            )
            ->with('user:id,first_name,last_name,email,avatar')
            ->where('statut', 'termine')
            ->groupBy('user_id')
            ->orderByDesc('score_moyen')
            ->limit(10)
            ->get();

        // ── Liste étudiants avec leur progression (paginée) ──
        $query = User::role('student')
            ->with(['abonnements' => fn($q) => $q->where('actif', true)->where('fin_at', '>=', now())->latest()])
            ->withCount([
                'abonnements as a_abonnement_actif' => fn($q) =>
                    $q->where('actif', true)->where('fin_at', '>=', now()),
            ])
            ->orderBy('created_at', 'desc');

        // Filtre recherche
        if (!empty($filtres['search'])) {
            $s = $filtres['search'];
            $query->where(fn($q) =>
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
            );
        }

        $etudiants = $query->paginate(15)->withQueryString();

        // Charger les stats de passage pour chaque étudiant
        $userIds  = $etudiants->pluck('id');
        $passagesStats = LanguePassage::whereIn('user_id', $userIds)
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN statut="termine" THEN 1 ELSE 0 END) as termines'),
                DB::raw('ROUND(AVG(CASE WHEN statut="termine" THEN score END), 0) as score_moyen'),
                DB::raw('MAX(created_at) as derniere_activite')
            )
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        return compact('statsGlobales', 'topEtudiants', 'etudiants', 'passagesStats');
    }

    // ════════════════════════════════════════════════════════════
    //  DETAIL D'UN ÉTUDIANT (Admin)
    // ════════════════════════════════════════════════════════════

    public function getDetailEtudiant(User $user): array
    {
        $passages = LanguePassage::where('user_id', $user->id)
            ->with(['langue:id,code,nom,couleur', 'discipline:id,nom', 'serie:id,titre'])
            ->latest()
            ->get();

        $langues = Langue::orderBy('ordre')->get();

        // Progression par langue
        $progressionParLangue = [];
        foreach ($langues as $langue) {
            $passagesLangue = $passages->where('langue_id', $langue->id);
            if ($passagesLangue->isEmpty()) continue;

            $progressionParLangue[$langue->code] = [
                'langue'        => $langue,
                'total'         => $passagesLangue->count(),
                'termines'      => $passagesLangue->where('statut', 'termine')->count(),
                'score_moyen'   => (int) round($passagesLangue->where('statut','termine')->avg('score') ?? 0),
                'meilleur'      => (int) ($passagesLangue->where('statut','termine')->max('score') ?? 0),
                'passages'      => $passagesLangue->values(),
            ];
        }

        // Stats globales
        $termines = $passages->where('statut', 'termine');
        $stats = [
            'total'         => $passages->count(),
            'termines'      => $termines->count(),
            'score_moyen'   => (int) round($termines->avg('score') ?? 0),
            'meilleur'      => (int) ($termines->max('score') ?? 0),
            'temps_minutes' => (int) round($termines->sum('duree_secondes') / 60),
        ];

        $activiteRecente = $termines->take(10);

        $recommandations = $this->getRecommandations($user->id, $passages);

        return compact('user', 'langues', 'progressionParLangue', 'stats', 'passages','activiteRecente','recommandations');
    }

    // ════════════════════════════════════════════════════════════
    //  HELPERS PRIVÉS
    // ════════════════════════════════════════════════

    private function getProgressionParLangue(int $userId, Collection $passages): array
    {
        $langues = Langue::where('actif', true)->orderBy('ordre')->get();
        $result  = [];

        foreach ($langues as $langue) {
            $passagesLangue = $passages->where('langue_id', $langue->id);
            $termines       = $passagesLangue->where('statut', 'termine');

            $result[] = [
                'langue'      => $langue,
                'total'       => $passagesLangue->count(),
                'termines'    => $termines->count(),
                'score_moyen' => (int) round($termines->avg('score') ?? 0),
                'progression' => $passagesLangue->count() > 0
                    ? min(100, (int) round(($termines->count() / max($passagesLangue->count(), 1)) * 100))
                    : 0,
                'derniere_activite' => $passagesLangue->max('created_at'),
            ];
        }

        return $result;
    }

    private function getSerieFavorite(Collection $passagesTermines): ?string
    {
        if ($passagesTermines->isEmpty()) return null;
        $grouped = $passagesTermines->groupBy('serie_id');
        $top     = $grouped->sortByDesc(fn($g) => $g->count())->first();
        return $top?->first()?->serie?->titre ?? null;
    }

    private function calculerStreak(int $userId): int
    {
        // Récupère les jours distincts d'activité (30 derniers jours)
        $jours = LanguePassage::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as jour')
            ->distinct()
            ->orderByDesc('jour')
            ->pluck('jour')
            ->toArray();

        if (empty($jours)) return 0;

        $streak = 0;
        $date   = now()->startOfDay();

        foreach ($jours as $jour) {
            if ($date->toDateString() === $jour || $date->subDay()->toDateString() === $jour) {
                $streak++;
                $date = \Carbon\Carbon::parse($jour)->startOfDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    private function getRecommandations(int $userId, Collection $passages): Collection
    {
        $seriesVues = $passages->pluck('serie_id')->unique()->filter()->toArray();

        return \App\Models\LangueSerie::with(['discipline.langue'])
            ->where('active', true)
            ->where('gratuite', true)
            ->whereNotIn('id', $seriesVues)
            ->limit(3)
            ->get();
    }
}
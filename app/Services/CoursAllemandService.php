<?php
// app/Services/CoursAllemandService.php

namespace App\Services;

use App\Models\CoursAllemand;
use App\Models\Course;
use App\Models\CoursLecon;
use App\Models\UserLessonProgress;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Service dédié aux cours d'allemand.
 * Toute la logique métier ici → réutilisable par Web ET API Mobile.
 */
class CoursAllemandService
{
    // ── Lister les cours avec progression de l'utilisateur ──

    public function listeCoursAvecProgression(?int $userId): \Illuminate\Support\Collection
    {
        $cours = Course::actifs()
            ->withCount('lessons')
            ->with(['lessons:id,course_id'])
            ->get();

        if (!$userId) {
            return $cours->map(function ($c) {
                $c->progression     = 0;
                $c->lecons_termines = 0;
                return $c;
            });
        }

        $progresParCours = CoursProgres::where('user_id', $userId)
            ->where('statut', 'termine')
            ->selectRaw('course_id, COUNT(*) as nb')
            ->groupBy('course_id')
            ->pluck('nb', 'course_id');

        return $cours->map(function ($c) use ($progresParCours) {
            $termines           = $progresParCours[$c->id] ?? 0;
            $c->lecons_termines = $termines;
            $c->progression     = $c->lecons_count > 0
                ? (int) round(($termines / $c->lecons_count) * 100)
                : 0;
            return $c;
        });
    }

    // ── Marquer une leçon comme commencée ──

    public function commencerLecon(Lesson $lecon, int $userId): UserLessonProgress
    {
        return UserLessonProgress::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lecon->id],
            [
                'cours_id'    => $lecon->cours_id,
                'statut'      => 'commence',
                'commence_at' => now(),
            ]
        );
    }

    // ── Valider une leçon avec score ──

    public function validerLecon(
        Lesson $lecon,
        int        $userId,
        int        $score,
        array      $reponsesUtilisateur = []
    ): UserLessonProgress {
        $pointsGagnes = $this->calculerPoints($lecon, $score);

        $progres = UserLessonProgress::updateOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lecon->id],
            [
                'cours_id'     => $lecon->cours_id,
                'statut'       => 'termine',
                'score'        => $score,
                'points_gagnes'=> $pointsGagnes,
                'commence_at'  => now(),
                'termine_at'   => now(),
            ]
        );

        return $progres;
    }

    // ── Calculer les points selon le score ──

    private function calculerPoints(Lesson $lecon, int $score): int
    {
        $base = $lecon->points_recompense;
        return match(true) {
            $score >= 90 => $base,
            $score >= 70 => (int) round($base * 0.8),
            $score >= 50 => (int) round($base * 0.6),
            default      => (int) round($base * 0.3),
        };
    }

    // ── Stats globales de l'utilisateur ──

    public function statsUtilisateur(int $userId): array
    {
        return [
            'lecons_terminees'  => CoursProgres::where('user_id', $userId)
                                               ->where('statut', 'termine')
                                               ->count(),
            'points_totaux'     => CoursProgres::where('user_id', $userId)
                                               ->sum('score_final') ?: 0,
            'score_moyen'       => (int) round(
                CoursProgres::where('user_id', $userId)
                            ->where('statut', 'termine')
                            ->avg('score_final') ?? 0
            ),
            'cours_en_cours'    => CoursProgres::where('user_id', $userId)
                                               ->distinct('course_id')
                                               ->count('course_id'),
        ];
    }
}
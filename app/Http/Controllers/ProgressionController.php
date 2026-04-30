<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseProgression;
use App\Models\Lesson;
use App\Models\LessonProgression;
use Illuminate\Support\Facades\DB;

class ProgressionController extends Controller
{

    // ── Tableau de bord global ────────────────────────────────────
    public function index()
    {
        $userId = auth()->id();

        // Toutes les progressions de cours de l'utilisateur
        $coursProgressions = CourseProgression::where('user_id', $userId)
            ->with('cours')
            ->orderByDesc('updated_at')
            ->get();

        // Stats globales
        $stats = [
            'lecons_terminees'  => LessonProgression::where('user_id', $userId)->where('statut', 'terminee')->count(),
            'points_total'      => LessonProgression::where('user_id', $userId)->sum('points_gagnes'),
            'score_moyen'       => LessonProgression::where('user_id', $userId)->where('total_questions', '>', 0)->avg('score'),
            'cours_termines'    => CourseProgression::where('user_id', $userId)->where('termine', true)->count(),
            'cours_en_cours'    => CourseProgression::where('user_id', $userId)->where('termine', false)->where('lecons_terminees', '>', 0)->count(),
            'temps_estime_min'  => LessonProgression::where('user_id', $userId)
                ->where('statut', 'terminee')
                ->join('lessons', 'lesson_progressions.lesson_id', '=', 'lessons.id')
                ->sum('lessons.duree_estimee_minutes'),
        ];

        // Dernières leçons complétées (activité récente)
        $activiteRecente = LessonProgression::where('user_id', $userId)
            ->whereNotNull('terminee_le')
            ->with(['lesson.cours'])
            ->orderByDesc('terminee_le')
            ->take(8)
            ->get();

        // Cours disponibles non commencés
        $coursDisponibles = Course::where('publie', true)
            ->whereNotIn('id', $coursProgressions->pluck('cours_id'))
            ->orderBy('ordre')
            ->take(3)
            ->get();

        return view('progression.index', compact(
            'coursProgressions', 'stats', 'activiteRecente', 'coursDisponibles'
        ));
    }

    // ── Détail progression d'un cours ─────────────────────────────
    public function cours(Course $cours)
    {
        $userId = auth()->id();

        $coursProgression = CourseProgression::where('user_id', $userId)
            ->where('cours_id', $cours->id)
            ->first();

        // Toutes les leçons publiées avec leur progression
        $lecons = Lesson::where('cours_id', $cours->id)
            ->where('publiee', true)
            ->orderBy('ordre')
            ->get();

        $lessonProgressions = LessonProgression::where('user_id', $userId)
            ->whereIn('lesson_id', $lecons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');  // [lesson_id => LessonProgression]

        // Stats du cours
        $stats = [
            'total'         => $lecons->count(),
            'terminees'     => $lessonProgressions->where('statut', 'terminee')->count(),
            'en_cours'      => $lessonProgressions->where('statut', 'en_cours')->count(),
            'points_gagnes' => $lessonProgressions->sum('points_gagnes'),
            'points_total'  => $lecons->sum('points_recompense'),
            'score_moyen'   => $lessonProgressions->where('total_questions', '>', 0)->avg('score'),
        ];

        return view('progression.cours', compact(
            'cours', 'lecons', 'lessonProgressions', 'coursProgression', 'stats'
        ));
    }

    // ── Détail d'une leçon : réponses données ─────────────────────
    public function lecon(Lesson $lecon)
    {
        $userId = auth()->id();

        $progression = LessonProgression::where('user_id', $userId)
            ->where('lesson_id', $lecon->id)
            ->firstOrFail();

        $cours = $lecon->cours;

        // Fusionner les réponses de l'étudiant avec les exercices de la leçon
        $exercices = collect($lecon->exercices ?? [])->map(function ($ex, $idx) use ($progression) {
            $repEtudiant = collect($progression->reponses_etudiant ?? [])
                ->firstWhere('index', $idx);
            return array_merge($ex, [
                'reponse_donnee' => $repEtudiant['reponse_donnee'] ?? null,
                'correct'        => $repEtudiant['correct'] ?? null,
            ]);
        });

        return view('progression.lecon', compact('lecon', 'cours', 'progression', 'exercices'));
    }
}
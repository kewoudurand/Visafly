<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseProgression;
use App\Models\Lesson;
use App\Models\UserCourseProgress;
use App\Models\UserLessonProgress;
use App\Models\LanguePassage;
use App\Models\Langue;
use App\Models\LessonProgression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ─────────────────────────────────────────────────────────────────────────────
//  FICHIER : app/Http/Controllers/Student/StudentCourseController.php
//  Page de progression de l'étudiant (cours commencés + terminés)
// ─────────────────────────────────────────────────────────────────────────────

class CourseDashboardController extends Controller
{
    // ════════════════════════════════════════
    //  PAGE PRINCIPALE : Mes cours & Progression
    // ════════════════════════════════════════
    public function index()
    {
        $userId = Auth::id();

        // ── Cours (plateforme instructor) ──────────────────────────────
        $coursEnCours = CourseProgression::where('user_id', $userId)
            ->where('statut', 'en_cours')
            ->with(['course.langue', 'course.lessons'])
            ->orderByDesc('derniere_activite_at')
            ->get();

        $coursTermines = CourseProgression::where('user_id', $userId)
            ->where('statut', 'termine')
            ->with(['course.langue'])
            ->orderByDesc('fin_at')
            ->get();

        // ── Séries d'examens (langue_passages) ──────────────────────────
        $passages = LanguePassage::where('user_id', $userId)
            ->with(['langue:id,code,nom,couleur', 'discipline:id,nom,code', 'serie:id,titre'])
            ->latest('created_at')
            ->get();

        $passagesEnCours  = $passages->where('statut', 'en_cours');
        $passagesTermines = $passages->where('statut', 'termine');

        // ── Stats globales ──────────────────────────────────────────────
        $stats = [
            'cours_en_cours'      => $coursEnCours->count(),
            'cours_termines'      => $coursTermines->count(),
            'series_en_cours'     => $passagesEnCours->count(),
            'series_terminees'    => $passagesTermines->count(),
            'score_moyen'         => (int) round($passagesTermines->avg('score') ?? 0),
            'total_activites'     => $passages->count() + $coursEnCours->count() + $coursTermines->count(),
        ];

        // ── Progression par langue (séries examen) ─────────────────────
        $langues = Langue::where('actif', true)->orderBy('ordre')->get();
        $progressionExamens = [];
        foreach ($langues as $langue) {
            $passLangue = $passages->where('langue_id', $langue->id);
            if ($passLangue->isEmpty()) continue;
            $term = $passLangue->where('statut', 'termine');
            $progressionExamens[] = [
                'langue'      => $langue,
                'total'       => $passLangue->count(),
                'termines'    => $term->count(),
                'score_moyen' => (int) round($term->avg('score') ?? 0),
                'progression' => $passLangue->count() > 0
                    ? min(100, (int) round(($term->count() / max($passLangue->count(),1)) * 100))
                    : 0,
            ];
        }

        // ── Cours disponibles non commencés ────────────────────────────
        $coursCommencesIds = CourseProgression::where('user_id', $userId)
            ->pluck('course_id')->toArray();

        $coursDisponibles = Course::where('publie', true)
            ->whereNotIn('id', $coursCommencesIds)
            ->with(['langue', 'instructor:id,first_name,last_name'])
            ->withCount('lessons')
            ->orderBy('niveau')
            ->limit(6)
            ->get();

        return view('student.courses.mes-cours', compact(
            'stats', 'coursEnCours', 'coursTermines',
            'passagesEnCours', 'passagesTermines',
            'progressionExamens', 'coursDisponibles'
        ));
    }

    // ════════════════════════════════════════
    //  VOIR UNE LEÇON
    // ════════════════════════════════════════
    public function showLesson(Course $course, Lesson $lesson)
    {
        $userId = Auth::id();

        // Marquer comme vue
        LessonProgression::updateOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lesson->id],
            ['vue' => true]
        );

        // Mettre à jour la progression du cours
        $this->updateCourseProgress($course, $userId);

        $lesson->load(['quizzes', 'course']);

        // Leçons précédente / suivante
        $lessons    = $course->lessons()->where('publiee', true)->get();
        $currentIdx = $lessons->search(fn($l) => $l->id === $lesson->id);
        $previous   = $currentIdx > 0 ? $lessons[$currentIdx - 1] : null;
        $next       = $currentIdx < $lessons->count() - 1 ? $lessons[$currentIdx + 1] : null;

        $isLast     = $next === null;
        $progression = CourseProgression::where('user_id', $userId)
            ->where('course_id', $course->id)->first();

        return view('student.courses.lesson', compact(
            'course', 'lesson', 'previous', 'next', 'isLast', 'progression', 'lessons', 'currentIdx'
        ));
    }

    // ════════════════════════════════════════
    //  TERMINER UNE LEÇON (POST)
    // ════════════════════════════════════════
    public function terminerLesson(Request $request, Course $course, Lesson $lesson)
    {   
        $userId = Auth::id();

        $progressionLecon = LessonProgression::updateOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lesson->id],
            [
                'vue'         => true,
                'terminee'    => true,
                'terminee_at' => now(),
            ]
        );

        // Traiter le quiz si présent
        if ($lesson->has_quiz && $request->filled('reponses')) {
            $quizzes    = $lesson->quizzes;
            $bonnes     = 0;
            foreach ($request->reponses as $quizId => $reponse) {
                $quiz = $quizzes->find($quizId);
                if ($quiz && (int)$reponse === $quiz->bonne_reponse) {
                    $bonnes++;
                }
            }
            $score = $quizzes->count() > 0
                ? (int) round(($bonnes / $quizzes->count()) * 100)
                : 100;

            $progressionLecon->update([
                'score_quiz'  => $score,
                'quiz_reussi' => $score >= 60,
            ]);
        }

        // Mettre à jour progression du cours
        $courseProgress = $this->updateCourseProgress($course, $userId);

        // Si c'est la dernière leçon → cours terminé
        $totalLecons  = $course->lessons()->where('publiee', true)->count();
        $terminees    = LessonProgression::where('user_id', $userId)
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->where('terminee', true)->count();

        if ($terminees >= $totalLecons) {
            $courseProgress->update([
                'statut'        => 'termine',
                'progression_pct' => 100,
                'fin_at'        => now(),
            ]);
            return redirect()
                ->route('student.courses.index')
                ->with('success', "🎉 Félicitations ! Vous avez terminé le cours « {$course->titre} » !");
        }

        // Aller à la leçon suivante
        $lessons    = $course->lessons()->where('publiee', true)->orderBy('ordre')->get();
        $currentIdx = $lessons->search(fn($l) => $l->id === $lesson->id);
        $next       = $currentIdx < $lessons->count() - 1 ? $lessons[$currentIdx + 1] : null;

        if ($next) {
            return redirect()
                ->route('student.lesson', [$course, $next])
                ->with('success', 'Leçon terminée ! Continuez avec la suivante.');
        }

        return redirect()
            ->route('student.courses.index')
            ->with('success', 'Leçon terminée !');
    }

    // ════════════════════════════════════════
    //  COMMENCER UN COURS
    // ════════════════════════════════════════
    public function commencerCours(Course $course)
    {
        $userId = Auth::id();

        CourseProgression::firstOrCreate(
            ['user_id' => $userId, 'course_id' => $course->id],
            [
                'statut'        => 'en_cours',
                'progression_pct' => 0,
                'lecons_total'  => $course->lessons()->where('publiee', true)->count(),
                'debut_at'      => now(),
                'derniere_activite_at' => now(),
            ]
        );

        $firstLesson = $course->lessons()->where('publiee', true)->orderBy('ordre')->first();

        if ($firstLesson) {
            return redirect()->route('student.lesson', [$course, $firstLesson]);
        }

        return back()->with('error', 'Ce cours ne contient pas encore de leçons.');
    }

    public function progress()
    {
        $userId = Auth::id();
    
        // Passages en cours (statut != termine)
        $enCours = \App\Models\Course::with(['langue','titre','niveau'])
            ->where('user_id', $userId)
            ->whereNull('fin_at')
            ->orderByDesc('created_at')
            ->get();
    
        // Passages terminés
        $termines = \App\Models\Course::with(['langue','serie','discipline'])
            ->where('user_id', $userId)
            ->whereNotNull('fin_at')
            ->orderByDesc('fin_at')
            ->get();
    
        // Stats globales
        $stats = [
            'total_cours_commences' => $enCours->count(),
            'total_cours_termines'  => $termines->count(),
            'score_moyen'           => $termines->count()
                ? (int) $termines->avg('score')
                : 0,
        ];
    
        return view('student.courses.progress', compact('enCours', 'termines', 'stats'));
    }

    // ════════════════════════════════════════
    //  HELPER PRIVÉ : Mettre à jour la progression
    // ════════════════════════════════════════
    private function updateCourseProgress(Course $course, int $userId): CourseProgression
    {
        $totalLecons = $course->lessons()->where('publiee', true)->count();
        $terminees   = LessonProgression::where('user_id', $userId)
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->where('terminee', true)->count();

        $pct = $totalLecons > 0
            ? min(100, (int) round(($terminees / $totalLecons) * 100))
            : 0;

        $avgScore = LessonProgression::where('user_id', $userId)
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->whereNotNull('score_quiz')
            ->avg('score_quiz');

        return CourseProgression::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $course->id],
            [
                'statut'                => $pct >= 100 ? 'termine' : 'en_cours',
                'progression_pct'       => $pct,
                'lecons_terminees'      => $terminees,
                'lecons_total'          => $totalLecons,
                'score_quiz_moyen'      => $avgScore ? (int) round($avgScore) : null,
                'derniere_activite_at'  => now(),
            ]
        );
    }
}
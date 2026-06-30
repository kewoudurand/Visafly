<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseProgression;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // Récupérer la liste des cours avec leur progression pour l'étudiant
    public function index()
    {
        $userId = Auth::id();

        // On récupère tous les cours publiés
        $cours = Course::where('publie', true)
            ->with(['instructor:id,first_name,last_name', 'lessons' => function($q) {
                $q->where('publiee', true);
            }])
            ->get();

        // On récupère les progressions de l'utilisateur pour ces cours
        $progressions = CourseProgression::where('user_id', $userId)
            ->get()
            ->keyBy('cours_id');

        $data = $cours->map(function ($c) use ($progressions) {
            $prog = $progressions->get($c->id);
            return [
                'id' => $c->id,
                'titre' => $c->titre,
                'description' => $c->description,
                'niveau' => $c->niveau,
                'progression' => $prog ? $prog->progression_pct : 0,
                'statut' => $prog ? $prog->statut : 'non_commence',
                'nb_lecons' => $c->lessons->count(),
                'professeur' => $c->instructor ? [
                    'id' => $c->instructor->id,
                    'first_name' => $c->instructor->first_name,
                    'last_name' => $c->instructor->last_name,
                ] : null,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    // Récupérer le détail d'un cours avec ses leçons et le statut de chaque leçon
    public function show(Course $cours)
    {
        $userId = Auth::id();

        $cours->load([
            'lessons' => fn ($q) => $q
                ->where('publiee', true)
                ->orderBy('ordre')
        ]);

        $progressions = \App\Models\LessonProgression::where('user_id', $userId)
            ->whereIn('lesson_id', $cours->lessons->pluck('id'))
            ->get()
            ->keyBy('lesson_id');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $cours->id,
                'titre' => $cours->titre,
                'description' => $cours->description,
                'niveau' => $cours->niveau,
                'langue' => '',
                'nb_apprenants' => 0,
                'progression' => 0,

                'lecons' => $cours->lessons->map(function ($lesson) use ($progressions) {

                    $prog = $progressions->get($lesson->id);

                    return [
                        'id' => $lesson->id,
                        'cours_id' => $lesson->cours_id,
                        'titre' => $lesson->titre,
                        'description' => $lesson->contenu,
                        'type' => $lesson->type,
                        'ordre' => $lesson->ordre,
                        'statut' => $prog?->statut,
                    ];
                }),
            ],
        ]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursProgres;
use App\Models\User;
use App\Services\CourseProgressionService;
use Illuminate\Http\Request;

// ─────────────────────────────────────────────────────────────────────────────
//  FICHIER : app/Http/Controllers/Admin/StudentProgressController.php
// ─────────────────────────────────────────────────────────────────────────────

class StudentProgressController extends Controller
{
    public function __construct(
        private readonly CourseProgressionService $service
    ) {}

    /**
     * Vue d'ensemble admin — tous les étudiants et leur progression
     */
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view analytics'), 403);

        $filtres = ['search' => $request->search];
        $data    = $this->service->getDashboardAdmin($filtres);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return view('admin.courses.student-progress', $data);
    }

    /**
     * Détail de la progression d'un étudiant spécifique
     */
    public function show(User $user)
    {
        // 1. Récupérer la progression réelle de l'utilisateur sur chaque cours
        // On charge les relations pour afficher le titre du cours et la langue associée
        $progressions = CoursProgres::with(['course.langue'])
            ->where('user_id', $user->id)
            ->get();

        $progressionParCours = [];

        foreach ($progressions as $p) {
            // On vérifie que le cours existe pour éviter les erreurs
            if (!$p->course) continue;

            $progressionParCours[] = [
                'titre'             => $p->course->titre,
                'langue'            => $p->course->langue?->nom ?? 'N/A',
                'pourcentage'       => $p->pourcentage, // Ta colonne 0-100
                'statut'            => $p->statut,      // 'ouvert', 'termine', etc.
                'score'             => $p->note_moyenne,
                'derniere_activite' => $p->derniere_activite_at,
                'temps_passe'       => (int) ($p->temps_total_secondes / 60), // En minutes
            ];
        }

        // 2. Stats globales pour le tableau de bord de l'étudiant
        $stats = [
            'cours_entames'   => $progressions->count(),
            'cours_termines'  => $progressions->where('statut', 'termine')->count(),
            'moyenne_generale'=> $progressions->avg('note_moyenne') ?? 0,
            'temps_total_h'   => round($progressions->sum('temps_total_secondes') / 3600, 1),
        ];

        return view('admin.courses.student-detail', compact('user', 'progressionParCours', 'stats'));
    }
}
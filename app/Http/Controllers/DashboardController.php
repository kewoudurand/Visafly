<?php

namespace App\Http\Controllers;

use App\Models\LanguePassage;
use App\Models\LangueAbonnement;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Page d'accueil dashboard étudiant
     */
    public function index()
    {
        $user = Auth::user();

        // ✅ CORRIGÉ : Stats pour le student
        $stats = [
            // Tests passés (passage terminés)
            'tests_passes' => LanguePassage::where('user_id', $user->id)
                                           ->where('statut', 'termine')
                                           ->count(),

            // Score moyen
            'score_moyen' => (int) round(
                LanguePassage::where('user_id', $user->id)
                             ->where('statut', 'termine')
                             ->avg('score') ?? 0
            ),

            // Consultations
            'consultations_total' => Consultation::where('user_id', $user->id)->count(),

            // Abonnement actif
            'abonnement_actif' => LangueAbonnement::where('user_id', $user->id)
                                                   ->where('actif', true)
                                                   ->where('fin_at', '>=', now())
                                                   ->exists(),
        ];

        // ✅ CORRIGÉ : Passages récents (10 derniers tests)
        $passages = LanguePassage::with([
            'serie:id,nom',
            'discipline:id,nom',
            'langue:id,code,nom,couleur'
        ])
            ->where('user_id', $user->id)
            ->where('statut', 'termine')
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Consultations récentes
        $consultations = Consultation::where('user_id', $user->id)
            ->latest('created_at')
            ->limit(5)
            ->get();

        // Abonnement actif
        $abonnement = LangueAbonnement::with(['plan', 'langue'])
            ->where('user_id', $user->id)
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->latest('debut_at')
            ->first();

        return view('users.dashboard', compact(
            'stats',
            'passages',
            'consultations',
            'abonnement'
        ));
    }
}
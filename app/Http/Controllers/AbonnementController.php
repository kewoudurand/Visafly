<?php

namespace App\Http\Controllers;

use App\Models\Langue;
use App\Models\PlanAbonnement;
use App\Models\LangueAbonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AbonnementController extends Controller
{
    // ════════════════════════════════════════
    //  INDEX - Page abonnement de l'utilisateur
    // ════════════════════════════════════════
    public function index()
    {
        $user = Auth::user();

        // ✅ CORRIGÉ : Utiliser LangueAbonnement au lieu de TcfAbonnement
        $abonnement = LangueAbonnement::with(['plan', 'langue'])
            ->where('user_id', $user->id)
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->latest('debut_at')
            ->first();

        // Plans disponibles
        $plans = PlanAbonnement::where('actif', true)
            ->orderBy('ordre')
            ->get();

        // Langues disponibles
        $langues = Langue::where('actif', true)
            ->orderBy('ordre')
            ->get();

        // Historique des abonnements
        $historique = LangueAbonnement::with(['plan', 'langue'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        return view('users.abonnement', compact('abonnement', 'plans', 'langues', 'historique'));
    }

    // ════════════════════════════════════════
    //  SOUSCRIRE À UN PLAN
    // ════════════════════════════════════════
    public function souscrire(Request $request, PlanAbonnement $plan)
    {
        $user = Auth::user();

        $request->validate([
            'langue_id' => 'nullable|exists:langues,id',
        ]);

        // ✅ CORRIGÉ : Désactiver les anciens abonnements
        LangueAbonnement::where('user_id', $user->id)
            ->update(['actif' => false]);

        // ✅ CORRIGÉ : Créer le nouvel abonnement
        $abonnement = LangueAbonnement::create([
            'user_id'           => $user->id,
            'plan_id'           => $plan->id,
            'langue_id'         => $request->langue_id,
            'code'              => $plan->code,
            'montant'           => $plan->prix,
            'devise'            => $plan->devise,
            'debut_at'          => now(),
            'fin_at'            => now()->addDays($plan->duree_jours),
            'actif'             => true,
            'reference_paiement' => 'VF-' . strtoupper(Str::random(8)),
            'methode_paiement'  => 'en_attente',
            'statut_paiement'   => 'en_attente', // En attente de paiement Flutterwave
        ]);

        // TODO: Rediriger vers Flutterwave pour le paiement
        // Pour l'instant, on confirme directement (à remplacer)
        $abonnement->update(['statut_paiement' => 'confirme']);

        return back()->with('success',
            "Abonnement {$plan->nom} souscrit ! Durée : {$plan->duree_jours} jours."
        );
    }

    // ════════════════════════════════════════
    //  RENOUVELER UN ABONNEMENT
    // ════════════════════════════════════════
    public function renouveler(LangueAbonnement $abonnement)
    {
        // Vérifier que c'est l'utilisateur
        abort_unless($abonnement->user_id === auth()->id(), 403);

        $plan = $abonnement->plan;

        // Créer un nouvel abonnement
        LangueAbonnement::create([
            'user_id'           => auth()->id(),
            'plan_id'           => $plan->id,
            'langue_id'         => $abonnement->langue_id,
            'code'              => $plan->code,
            'montant'           => $plan->prix,
            'devise'            => $plan->devise,
            'debut_at'          => now(),
            'fin_at'            => now()->addDays($plan->duree_jours),
            'actif'             => true,
            'reference_paiement' => 'VF-' . strtoupper(Str::random(8)),
            'methode_paiement'  => 'en_attente',
            'statut_paiement'   => 'en_attente',
        ]);

        return back()->with('success', 'Abonnement renouvelé avec succès !');
    }

    // ════════════════════════════════════════
    //  ANNULER UN ABONNEMENT
    // ════════════════════════════════════════
    public function annuler(LangueAbonnement $abonnement)
    {
        // Vérifier que c'est l'utilisateur
        abort_unless($abonnement->user_id === auth()->id(), 403);

        $abonnement->update([
            'actif' => false,
            'fin_at' => now(),
        ]);

        return back()->with('success', 'Abonnement annulé.');
    }
}
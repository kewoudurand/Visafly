<?php
// app/Http/Controllers/AbonnementController.php

namespace App\Http\Controllers;

use App\Models\Langue;
use App\Models\LangueAbonnement;
use App\Models\PlanAbonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AbonnementController extends Controller
{
    /**
     * Affiche la page "Mon abonnement" : abonnement(s) actif(s), historique, plans disponibles.
     */
    public function index()
    {
        $user = Auth::user();

        $abonnementsActifs = LangueAbonnement::where('user_id', $user->id)
            ->where('statut', 'actif')
            ->where('fin_at', '>=', now())
            ->with(['langue', 'plan'])
            ->get();

        // On récupère le premier abonnement actif s'il existe, sinon null
        $abonnement = $abonnementsActifs->first();

        $historique = LangueAbonnement::where('user_id', $user->id)
            ->whereIn('statut', ['actif', 'expire', 'annule']) // exclut les tentatives non abouties
            ->with(['langue', 'plan'])
            ->latest()
            ->get();

        $plans   = PlanAbonnement::where('actif', true)->orderBy('ordre')->get();
        $langues = Langue::orderBy('nom')->get();

        // On ajoute $abonnement dans le compact
        return view('abonnement.abonnement', compact('abonnementsActifs', 'abonnement', 'historique', 'plans', 'langues'));
    }

    /**
     * Étape 1 : l'utilisateur choisit un plan ET une langue (via le modal), on crée
     * l'abonnement en attente. C'est PaiementController qui prendra le relais ensuite.
     */
    public function souscrire(Request $request, PlanAbonnement $plan)
    {
        $request->validate([
            'langue_id' => 'required|exists:langues,id',
        ]);

        $user   = Auth::user();
        $langue = Langue::findOrFail($request->langue_id);

        // Empêche un doublon d'abonnement actif sur la même langue
        $dejaActif = LangueAbonnement::where('user_id', $user->id)
            ->where('langue_id', $langue->id)
            ->where('statut', 'actif')
            ->where('fin_at', '>=', now())
            ->exists();

        if ($dejaActif) {
            return back()->with('error', "Vous avez déjà un abonnement actif pour {$langue->nom}.");
        }

        $abonnement = LangueAbonnement::create([
            'user_id'  => $user->id,
            'langue_id' => $langue->id,
            'plan_id'  => $plan->id,
            'montant'  => $plan->prix,
            'devise'   => $plan->devise,
            'debut_at' => null,
            'fin_at'   => null,
            'statut'   => 'en_attente',
        ]);

        // Redirige vers le contrôleur de paiement, qui génère la session Notch Pay
        return redirect()->route('paiement.initier', $abonnement);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\LangueAbonnement;
use App\Models\Paiement;
use App\Services\NotchPayService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    public function __construct(private NotchPayService $notchPay) {}

    /**
     * Crée l'enregistrement de paiement (en_attente) et redirige vers Notch Pay.
     */
    public function initier(LangueAbonnement $abonnement)
    {
        abort_unless($abonnement->user_id === Auth::id(), 403);
        abort_unless($abonnement->statut === 'en_attente', 400, 'Cet abonnement a déjà été traité.');

        $reference = 'VF-PAY-' . strtoupper(Str::random(10));

        $paiement = Paiement::create([
            'user_id'              => Auth::id(),
            'langue_abonnement_id' => $abonnement->id,
            'reference'            => $reference,
            'montant'              => $abonnement->montant,
            'devise'               => $abonnement->devise,
            'statut'               => 'en_attente',
        ]);

        try {
            $session = $this->notchPay->creerPaiement([
                'montant'      => $paiement->montant,
                'devise'       => $paiement->devise,
                'reference'    => $reference,
                'email'        => Auth::user()->email,
                'nom'          => Auth::user()->name,
                'description'  => "Abonnement {$abonnement->plan->nom} — {$abonnement->langue->nom}",
                'callback_url' => route('paiement.retour', $paiement),
            ]);
        } catch (\Throwable $e) {
            $paiement->update(['statut' => 'echec']);
            return back()->with('error', "Erreur lors de l'initialisation du paiement : {$e->getMessage()}");
        }

        // Notch Pay renvoie généralement un champ 'authorization_url' — adapte selon leur réponse réelle
        $urlPaiement = $session['authorization_url'] ?? $session['transaction']['authorization_url'] ?? null;

        if (!$urlPaiement) {
            $paiement->update(['statut' => 'echec', 'reponse_gateway' => $session]);
            return back()->with('error', "Impossible d'obtenir l'URL de paiement.");
        }

        $paiement->update([
            'transaction_id'      => $session['transaction']['reference'] ?? $session['id'] ?? null,
            'reponse_gateway' => $session,
        ]);

        return redirect()->away($urlPaiement);
    }

    /**
     * Page de retour après paiement — NE VALIDE JAMAIS l'abonnement ici.
     * Sert uniquement à informer l'utilisateur ; seule la confirmation Webhook fait foi.
     */
    public function retour(Paiement $paiement)
    {
        abort_unless($paiement->user_id === Auth::id(), 403);

        $paiement->refresh();

        if ($paiement->statut === 'confirme') {
            return redirect()->route('abonnement.index')
                ->with('success', 'Paiement confirmé ! Votre abonnement est maintenant actif.');
        }

        return redirect()->route('abonnement.index')
            ->with('info', 'Votre paiement est en cours de vérification. Cela peut prendre quelques instants.');
    }
}
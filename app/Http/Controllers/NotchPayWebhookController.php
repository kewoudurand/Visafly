<?php
// app/Http/Controllers/NotchPayWebhookController.php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Services\NotchPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotchPayWebhookController extends Controller
{
    public function __construct(private NotchPayService $notchPay) {}

    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('x-notch-signature');

        if (!$this->notchPay->verifierSignatureWebhook($payload, $signature)) {
            Log::warning('NotchPay Webhook: signature invalide.', ['signature' => $signature]);
            return response()->json(['message' => 'Signature invalide'], 403);
        }

        $event = json_decode($payload, true);

        // Log complet pour connaître la structure exacte en sandbox — à retirer une fois validé
        Log::info('NotchPay Webhook reçu', $event ?? []);

        $type      = $event['type'] ?? null; // ex. 'payment.complete', 'payment.failed'
        $reference = $event['data']['reference'] ?? $event['reference'] ?? null;

        if (!$reference) {
            Log::warning('NotchPay Webhook: référence manquante.', $event ?? []);
            return response()->json(['message' => 'Référence manquante'], 400);
        }

        $paiement = Paiement::where('reference', $reference)->first();

        if (!$paiement) {
            Log::warning("NotchPay Webhook: paiement introuvable pour référence {$reference}");
            return response()->json(['message' => 'Paiement introuvable'], 404);
        }

        // Vérification directe systématique (recommandée par la doc), ne se fie jamais
        // uniquement au contenu du payload webhook.
        try {
            $verification  = $this->notchPay->verifierPaiement($reference);
            $statutVerifie = $verification['transaction']['status'] ?? null;
        } catch (\Throwable $e) {
            Log::error("NotchPay Webhook: échec vérification directe — {$e->getMessage()}");
            $statutVerifie = null;
        }

        $paiement->update(['reponse_gateway' => $event]);

        $estConfirme = $type === 'payment.complete' || $statutVerifie === 'complete';
        $estEchec    = $type === 'payment.failed' || in_array($statutVerifie, ['failed', 'canceled', 'expired'], true);

        if ($estConfirme) {
            $paiement->update(['statut' => 'confirme']);

            $abonnement = $paiement->abonnement;

            if ($abonnement->statut !== 'actif') {
                $abonnement->update([
                    'statut'   => 'actif',
                    'debut_at' => now(),
                    'fin_at'   => now()->addDays($abonnement->plan->duree_jours),
                ]);
            }

            Log::info("Abonnement {$abonnement->id} activé suite au paiement {$reference}");
        } elseif ($estEchec) {
            $paiement->update(['statut' => 'echec']);
        }

        return response()->json(['message' => 'Webhook received']);
    }
}
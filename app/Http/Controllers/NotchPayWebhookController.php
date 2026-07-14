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

        Log::info('NotchPay Webhook — payload brut', ['body' => $payload]);

        if (!$this->notchPay->verifierSignatureWebhook($payload, $signature)) {
            Log::warning('NotchPay Webhook: signature invalide.');
            return response()->json(['message' => 'Signature invalide'], 403);
        }

        $event = json_decode($payload, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('NotchPay Webhook: JSON invalide.');
            return response()->json(['message' => 'Payload invalide'], 400);
        }

        $type        = $event['event'] ?? null;
        $merchantRef = $event['data']['merchant_reference'] ?? null; // pour retrouver LE paiement en base
        $notchRef    = $event['data']['reference'] ?? null;          // ✅ pour interroger l'API Notch Pay
        $statut      = $event['data']['status'] ?? null;

        if (!$merchantRef) {
            Log::warning('NotchPay Webhook: merchant_reference manquante.', $event ?? []);
            return response()->json(['message' => 'Référence introuvable, ignoré'], 200);
        }

        $paiement = Paiement::where('reference', $merchantRef)->first();

        if (!$paiement) {
            Log::warning("NotchPay Webhook: paiement introuvable pour merchant_reference {$merchantRef}");
            return response()->json(['message' => 'Paiement introuvable'], 404);
        }

        if (!$paiement->transaction_id && $notchRef) {
            $paiement->update(['transaction_id' => $notchRef]);
        }

        // ✅ corrigé — on vérifie avec la référence Notch Pay, pas la nôtre
        $referencePourVerif = $paiement->transaction_id ?? $notchRef;

        try {
            $verification  = $this->notchPay->verifierPaiement($referencePourVerif);
            $statutVerifie = $verification['transaction']['status'] ?? $statut;
            Log::info('NotchPay Webhook — vérification directe', $verification);
        } catch (\Throwable $e) {
            Log::error("NotchPay Webhook: échec vérification directe — {$e->getMessage()}");
            $statutVerifie = $statut; // repli sur le statut fourni dans le webhook lui-même
        }

        $paiement->update(['reponse_gateway' => $event]);

        $estConfirme = $type === 'payment.complete' || $statutVerifie === 'complete';
        $estEchec    = $type === 'payment.failed' || in_array($statutVerifie, ['failed', 'canceled', 'expired'], true);

        if ($estConfirme && $paiement->statut !== 'confirme') {
            $paiement->update(['statut' => 'confirme']);

            $abonnement = $paiement->abonnement;

            if ($abonnement->statut !== 'actif') {
                $abonnement->update([
                    'statut'   => 'actif',
                    'debut_at' => now(),
                    'fin_at'   => now()->addDays($abonnement->plan->duree_jours),
                ]);
            }

            Log::info("Abonnement {$abonnement->id} activé suite au paiement {$merchantRef}");
        } elseif ($estEchec) {
            $paiement->update(['statut' => 'echec']);
        } else {
            Log::info("NotchPay Webhook: événement '{$type}' statut '{$statutVerifie}' — en attente pour {$merchantRef}");
        }

        return response()->json(['message' => 'OK']);
    }
}
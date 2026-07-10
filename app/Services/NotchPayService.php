<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class NotchPayService
{
    protected string $baseUrl;
    protected string $publicKey;
    protected string $privateKey;
    protected ?string $hashKey;

    public function __construct()
    {
        $this->baseUrl   = rtrim(config('services.notchpay.base_url'), '/');
        $this->publicKey = config('services.notchpay.public_key');
        $this->privateKey = config('services.notchpay.private_key');
        $this->hashKey   = config('services.notchpay.hash_key');

        Log::info('NotchPay configuration', [
            'base_url'          => $this->baseUrl,
            'public_key_loaded' => !empty($this->publicKey),
            'private_key_loaded'=> !empty($this->privateKey),
            'hash_key_loaded'   => !empty($this->hashKey),
        ]);
    }

    /**
     * Headers standards Notch Pay
     */
    protected function headers(): array
    {
        return [
            'Authorization' => $this->publicKey,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];
    }

    /**
     * Création d'un paiement
     */
    public function creerPaiement(array $data): array
    {
        $payload = [

            'amount'      => (float) $data['montant'],

            'currency'    => $data['devise'],

            'reference'   => $data['reference'],

            'description' => $data['description'] ?? 'Abonnement VisaFly',

            'callback'    => $data['callback_url'],

            'customer' => [
                'name'  => $data['nom'],
                'email' => $data['email'],
                'phone' => $data['telephone'] ?? null,
            ],

        ];

        Log::info('NotchPay Request', $payload);

        $response = Http::withHeaders($this->headers())
            ->timeout(60)
            ->post($this->baseUrl.'/payments', $payload);

        Log::info('NotchPay Response', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if (! $response->successful()) {

            throw new RuntimeException(
                'Erreur Notch Pay : '.$response->body()
            );
        }

        return $response->json();
    }

    /**
     * Vérifier un paiement
     */
    public function verifierPaiement(string $reference): array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout(30)
            ->get($this->baseUrl."/payments/{$reference}");

        if (! $response->successful()) {

            throw new RuntimeException(
                'Impossible de vérifier le paiement.'
            );
        }

        return $response->json();
    }

    /**
     * Vérification du webhook
     */
    public function verifierSignatureWebhook(
        string $payload,
        ?string $signature
    ): bool {

        if (!$signature || !$this->hashKey) {
            return false;
        }

        $expected = hash_hmac(
            'sha256',
            $payload,
            $this->hashKey
        );

        return hash_equals($expected, $signature);
    }
}
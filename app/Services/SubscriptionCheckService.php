<?php

namespace App\Services;
 
use App\Models\User;
 
class SubscriptionCheckService
{
    /**
     * ✅ Vérifier si l'utilisateur a un abonnement actif
     */
    public static function hasActiveSubscription(?User $user): bool
    {
        if (!$user) {
            return false;
        }
 
        // Admin et super-admin ont accès gratuit
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            return true;
        }
 
        // Vérifier dans la table subscriptions (adapter selon votre structure)
        return self::checkSubscription($user);
    }
 
    /**
     * 🔍 Vérifier l'abonnement selon votre structure BD
     * Adapter cette méthode à votre modèle
     */
    private static function checkSubscription(User $user): bool
    {
        // OPTION 1: Si vous avez une relation subscriptions
        if (method_exists($user, 'langue_abonnements')) {
            return $user->langue_abonnements()
                       ->where('statut_paiement', 'active')
                       ->where('fin_at', '>', now())
                       ->orWhere('fin_at', null) // Abonnement illimité
                       ->exists();
        }
 
        // OPTION 2: Si l'abonnement est dans une colonne
        if ($user->hasColumn('subscription_status')) {
            return $user->subscription_status === 'active';
        }
 
        // OPTION 3: Si c'est une relation subscription (singulier)
        if (method_exists($user, 'subscription')) {
            $subscription = $user->subscription;
            
            if (!$subscription) {
                return false;
            }
 
            return $subscription->status === 'active' &&
                   (!$subscription->ends_at || $subscription->ends_at > now());
        }
 
        // OPTION 4: Vérifier via une colonne subscription_expires_at
        if ($user->hasColumn('subscription_expires_at')) {
            return $user->subscription_expires_at && 
                   $user->subscription_expires_at > now();
        }
 
        return false;
    }
 
    /**
     * 📊 Obtenir les détails de l'abonnement actif
     */
    public static function getActiveSubscriptionDetails(User $user): array
    {
        if (!self::hasActiveSubscription($user)) {
            return [
                'active' => false,
                'plan_name' => null,
                'purchased_at' => null,
                'expires_at' => null,
                'days_remaining' => null,
            ];
        }
 
        // Récupérer l'abonnement selon votre structure
        if (method_exists($user, 'langue_abonnements')) {
            $subscription = $user->langue_abonnements()
                                ->where('statut_paiement', 'active')
                                ->latest()
                                ->first();
        } elseif (method_exists($user, 'subscription')) {
            $subscription = $user->subscription;
        } else {
            return [
                'active' => true,
                'plan_name' => 'Premium',
                'purchased_at' => null,
                'expires_at' => $user->subscription_expires_at ?? null,
                'days_remaining' => null,
            ];
        }
 
        if (!$subscription) {
            return [
                'active' => false,
                'plan_name' => null,
                'purchased_at' => null,
                'expires_at' => null,
                'days_remaining' => null,
            ];
        }
 
        return [
            'active' => true,
            'plan_name' => $subscription->plan ?? 'Premium',
            'purchased_at' => $subscription->starts_at ?? $subscription->created_at,
            'expires_at' => $subscription->fin_at ?? null,
            'days_remaining' => $subscription->fin_at 
                ? now()->diffInDays($subscription->fin_at)
                : null,
        ];
    }
 
    /**
     * 📧 Obtenir les plans disponibles pour la souscription
     */
    public static function getAvailablePlans(): array
    {
        // Adapter selon vos plans réels
        return [
            [
                'id' => 1,
                'name' => 'Plan Mensuel',
                'price' => 9999, // En Francs CFA
                'duration' => '1 mois',
                'features' => [
                    '✅ Accès à tous les cours',
                    '✅ Suivi de progression',
                    '✅ Support prioritaire',
                ],
            ],
            [
                'id' => 2,
                'name' => 'Plan Trimestriel',
                'price' => 24999,
                'duration' => '3 mois',
                'features' => [
                    '✅ Accès à tous les cours',
                    '✅ Suivi de progression',
                    '✅ Support prioritaire',
                    '✅ Économie: -17%',
                ],
            ],
            [
                'id' => 3,
                'name' => 'Plan Annuel',
                'price' => 89999,
                'duration' => '12 mois',
                'features' => [
                    '✅ Accès à tous les cours',
                    '✅ Suivi de progression',
                    '✅ Support VIP',
                    '✅ Certificats',
                    '✅ Économie: -25%',
                ],
            ],
        ];
    }
}
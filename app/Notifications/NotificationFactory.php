<?php

namespace App\Notifications;
 
use App\Models\User;
use App\Models\Notification;
 
class NotificationFactory
{
    /**
     * Créer une notification de parrainage complété
     */
    public static function referralCompleted(User $referrer, User $referred, $commission)
    {
        return Notification::create([
            'user_id' => $referrer->id,
            'type' => 'referral_completed',
            'title' => '👥 Parrainage Validé!',
            'message' => $referred->first_name . ' a effectué une action. Vous avez gagné ' . number_format($commission, 0) . ' F!',
            'icon' => 'success',
            'data' => [
                'referrer_id' => $referrer->id,
                'referred_id' => $referred->id,
                'commission' => $commission,
            ],
            'action_url' => route('affiliate.dashboard'),
            'action_label' => 'Voir mon Dashboard',
        ]);
    }
 
    /**
     * Créer une notification de retrait approuvé
     */
    public static function withdrawalApproved(User $user, $amount, $method)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_approved',
            'title' => '✅ Retrait Approuvé!',
            'message' => 'Votre demande de retrait de ' . number_format($amount, 0) . ' F via ' . self::getMethodLabel($method) . ' a été approuvée!',
            'icon' => 'success',
            'data' => [
                'amount' => $amount,
                'method' => $method,
            ],
            'action_url' => route('affiliate.withdraw.history'),
            'action_label' => 'Voir l\'historique',
        ]);
    }
 
    /**
     * Créer une notification de retrait en attente
     */
    public static function withdrawalPending(User $user, $amount)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_pending',
            'title' => '⏳ Retrait En Attente',
            'message' => 'Votre demande de retrait de ' . number_format($amount, 0) . ' F est en attente d\'approbation. L\'admin l\'examinera dans les 24-48h.',
            'icon' => 'info',
            'data' => [
                'amount' => $amount,
            ],
            'action_url' => route('affiliate.withdraw.history'),
            'action_label' => 'Voir le détail',
        ]);
    }
 
    /**
     * Créer une notification de commission en attente
     */
    public static function commissionPending(User $referrer, User $referred, $amount)
    {
        return Notification::create([
            'user_id' => $referrer->id,
            'type' => 'commission_pending',
            'title' => '⏳ Commission En Attente',
            'message' => $referred->first_name . ' a été parrainé. Commission: ' . number_format($amount, 0) . ' F (en attente de validation)',
            'icon' => 'info',
            'data' => [
                'referred_id' => $referred->id,
                'amount' => $amount,
            ],
            'action_url' => route('affiliate.dashboard'),
            'action_label' => 'Voir les détails',
        ]);
    }
 
    /**
     * Créer une notification système
     */
    public static function system(User $user, $title, $message, $actionUrl = null)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'icon' => 'info',
            'action_url' => $actionUrl,
            'action_label' => $actionUrl ? 'En savoir plus' : null,
        ]);
    }
 
    private static function getMethodLabel($method)
    {
        return match($method) {
            'orange_money' => '🟠 Orange Money',
            'mtn' => '🔴 MTN Mobile Money',
            'bank_transfer' => '🏦 Virement Bancaire',
            default => '❓ Autre',
        };
    }
}
 
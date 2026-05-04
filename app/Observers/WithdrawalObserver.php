<?php

namespace App\Observers;

use App\Models\AffiliateWithdrawal;
use App\Services\NotificationService;

class WithdrawalObserver
{
    /**
     * Déclenché quand un retrait est créé
     */
    public function created(AffiliateWithdrawal $withdrawal)
    {
        // Notifier l'utilisateur
        NotificationService::withdrawalInitiated(
            $withdrawal->user,
            $withdrawal->amount,
            $withdrawal->method
        );
    }

    /**
     * Déclenché quand un retrait est mis à jour
     */
    public function updated(AffiliateWithdrawal $withdrawal)
    {
        // Si le status change en 'approved'
        if ($withdrawal->isDirty('status') && $withdrawal->status === 'approved') {
            NotificationService::withdrawalApproved(
                $withdrawal->user,
                $withdrawal->amount,
                $withdrawal->method
            );
        }

        // Si le status change en 'failed'
        if ($withdrawal->isDirty('status') && $withdrawal->status === 'failed') {
            $reason = $withdrawal->notes ?? 'Raison non spécifiée';
            NotificationService::withdrawalRejected(
                $withdrawal->user,
                $withdrawal->amount,
                $reason
            );
        }
    }
}
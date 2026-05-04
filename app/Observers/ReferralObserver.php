<?php

namespace App\Observers;

use App\Models\Referral;
use App\Services\NotificationService;

class ReferralObserver
{
    /**
     * Déclenché quand une affiliation est validée
     */
    public function updated(Referral $referral)
    {
        // Si le status change en 'completed'
        if ($referral->isDirty('status') && $referral->status === 'completed') {
            NotificationService::affiliationCompleted(
                $referral->referrer,
                $referral->referred,
                $referral->commission
            );

            // Aussi envoyer une notification commission gagnée
            NotificationService::commissionEarned(
                $referral->referrer,
                $referral->referred,
                $referral->commission
            );
        }
    }
}
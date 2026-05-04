<?php

namespace App\Observers;

use App\Models\User;
use App\Services\NotificationService;

class UserObserver
{
    /**
     * Déclenché quand un utilisateur est créé
     */
    public function created(User $user)
    {
        // Si l'utilisateur a une référence de parrainage
        if ($user->referred_by) {
            $referrer = User::find($user->referred_by);
            
            if ($referrer) {
                // Notifier le parrain
                NotificationService::newStudentViaReferral(
                    $referrer,
                    $user
                );
            }
        }
    }
}
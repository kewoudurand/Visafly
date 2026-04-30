<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'commission',
        'status',
        'description',
    ];

    protected $casts = [
        'commission' => 'decimal:2',
    ];

    // Relations
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Marquer comme complétée et ajouter à la wallet
     */
    public function complete()
    {
        if ($this->status === 'pending') {
            $this->status = 'completed';
            $this->save();

            // Récupérer le wallet ou le créer s'il n'existe pas
            $wallet = $this->referrer->affiliateWallet()->firstOrCreate(
                ['user_id' => $this->referrer_id], // On cherche par l'ID du parrain
                [
                    'amount' => 0, 
                    'total_earned' => 0,
                    // Si 'referral_id' est vraiment requis dans cette table, ajoutez-le ici :
                    // 'referral_id' => $this->id 
                ]
            );

            // Maintenant, l'incrémentation est sécurisée
            $wallet->increment('amount', $this->commission);
            $wallet->increment('total_earned', $this->commission);
        }

        return $this;
    }

    /**
     * Marquer comme retiré
     */
    public function markAsWithdrawn()
    {
        $this->status = 'withdrawn';
        $this->save();

        // Mettre à jour le wallet
        $this->referrer->affiliateWallet->decrement('balance', $this->commission);
        $this->referrer->affiliateWallet->increment('total_withdrawn', $this->commission);

        return $this;
    }
}
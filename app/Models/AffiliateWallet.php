<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateWallet extends Model
{
    protected $fillable = ['user_id', 'amount', 'total_earned', 'total_withdrawn'];

    protected $casts = [
        'amount' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ajouter des fonds
     */
    public function addFunds($balance)
    {
        $this->increment('amount', $balance);
        $this->increment('total_earned', $balance);
        return $this;
    }

    /**
     * Retirer des fonds
     */
    public function withdrawFunds($amountToWithdraw) // Renommé pour plus de clarté
    {
        if ($this->amount >= $amountToWithdraw) {
            $this->decrement('amount', $amountToWithdraw);
            $this->increment('total_withdrawn', $amountToWithdraw);
            return true;
        }
        return false;
    }

    /**
     * Alias pour la colonne 'amount'
     */
    public function getBalanceAttribute()
    {
        return $this->amount;
    }

    /**
     * Obtenir les historiques des transactions
     */
    public function getTransactionHistory()
    {
        return $this->user->affiliateActivity()
                         ->where('status', '!=', 'pending')
                         ->orderBy('created_at', 'desc')
                         ->get();
    }
}

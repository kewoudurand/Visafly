<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $fillable = [
        'user_id', 'langue_abonnement_id', 'reference', 'transaction_id',
        'montant', 'devise', 'methode', 'statut', 'reponse_gateway',
    ];

    protected $casts = [
        'reponse_gateway' => 'array',
        'montant' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function abonnement(): BelongsTo
    {
        return $this->belongsTo(LangueAbonnement::class, 'langue_abonnement_id');
    }
}
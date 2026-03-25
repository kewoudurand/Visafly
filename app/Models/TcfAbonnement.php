<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TcfAbonnement extends Model
{
    protected $table = 'tcf_abonnements';
    protected $fillable = ['user_id','forfait','montant','devise','debut_at','fin_at','actif','reference_paiement'];
    protected $casts = ['debut_at' => 'datetime', 'fin_at' => 'datetime', 'actif' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }

    public static function userActif(int $userId): bool
    {
        return static::where('user_id', $userId)
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->exists();
    }
}
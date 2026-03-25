<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TcfSerie extends Model
{
    protected $table = 'tcf_series';
    protected $fillable = ['nom', 'code', 'type', 'gratuit', 'ordre', 'actif'];
    protected $casts = ['gratuit' => 'boolean', 'actif' => 'boolean'];

    public function disciplines(): HasMany
    {
        return $this->hasMany(TcfDiscipline::class, 'serie_id');
    }

    // Nombre de passages gratuits utilisés par l'user courant
    public static function passagesGratuits(int $userId): int
    {
        return TcfPassage::whereHas('discipline.serie', fn($q) => $q->where('gratuit', true))
            ->where('user_id', $userId)
            ->distinct('discipline_id')
            ->count('discipline_id');
    }
}
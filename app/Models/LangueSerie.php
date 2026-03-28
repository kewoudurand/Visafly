<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LangueSerie extends Model
{
    use SoftDeletes;

    protected $table = 'langue_series';

    protected $fillable = [
        'discipline_id', 'titre', 'description',
        'niveau', 'duree_minutes', 'nombre_questions',
        'gratuite', 'active', 'ordre',
    ];

    protected $casts = [
        'gratuite' => 'boolean',
        'active'   => 'boolean',
    ];

    // ── Relations ──

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(LangueDiscipline::class, 'discipline_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(LangueQuestion::class, 'serie_id')->orderBy('ordre');
    }

    // ── Helpers ──

    public function niveauLabel(): string
    {
        return match($this->niveau) {
            1 => 'Débutant (A1-A2)',
            2 => 'Intermédiaire (B1-B2)',
            3 => 'Avancé (C1-C2)',
            default => 'Niveau ' . $this->niveau,
        };
    }

    public function niveauColor(): string
    {
        return match($this->niveau) {
            1 => '#1cc88a',
            2 => '#F5A623',
            3 => '#E24B4A',
            default => '#888',
        };
    }
}
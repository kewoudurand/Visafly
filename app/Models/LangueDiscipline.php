<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LangueDiscipline extends Model
{
    protected $table = 'langue_disciplines';

    protected $fillable = [
        'langue_id', 'code', 'nom', 'nom_court', 'type',
        'has_audio', 'has_image', 'duree_minutes',
        'consigne', 'actif', 'ordre',
    ];

    protected $casts = [
        'actif'     => 'boolean',
        'has_audio' => 'boolean',
        'has_image' => 'boolean',
    ];

    // ── Relations ──

    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(LangueSerie::class, 'discipline_id')->orderBy('ordre');
    }

    // ── Helpers ──

    public function typeLabel(): string
    {
        return match($this->type) {
            'texte'      => 'Texte / Lecture',
            'audio'      => 'Audio / Écoute',
            'image'      => 'Image',
            'production' => 'Production',
            default      => ucfirst($this->type),
        };
    }

    public function typeIcon(): string
    {
        return match($this->type) {
            'audio'      => 'bi-headphones',
            'image'      => 'bi-image',
            'production' => 'bi-pencil-square',
            default      => 'bi-file-text',
        };
    }
}
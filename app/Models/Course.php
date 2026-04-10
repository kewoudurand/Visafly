<?php
// app/Models/Course.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'slug', 'titre', 'sous_titre', 'description',
        'niveau', 'couleur', 'icone',
        'duree_heures', 'gratuit', 'actif', 'ordre',
    ];

    protected $casts = [
        'gratuit' => 'boolean',
        'actif'   => 'boolean',
    ];

    // ── Relations ──────────────────────────────────────────────

    public function lecons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'cours_id')->orderBy('ordre');
    }

    public function progres(): HasMany
    {
        return $this->hasMany(CoursProgres::class, 'cours_id');
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeActifs(Builder $query): Builder
    {
        return $query->where('actif', true)->orderBy('ordre');
    }

    public function scopeGratuits(Builder $query): Builder
    {
        return $query->where('gratuit', true);
    }

    // ── Helpers ────────────────────────────────────────────────

    public function progressionPour(int $userId): int
    {
        $total    = $this->lecons()->count();
        $termines = CoursProgres::where('user_id', $userId)
                                ->where('cours_id', $this->id)
                                ->where('statut', 'termine')
                                ->count();

        return $total > 0 ? (int) round(($termines / $total) * 100) : 0;
    }

    public function niveauBadgeColor(): string
    {
        return match($this->niveau) {
            'A1' => '#1cc88a',
            'A2' => '#54a3f3',
            'B1' => '#F5A623',
            'B2' => '#E24B4A',
            'C1' => '#7F77DD',
            default => '#888',
        };
    }
}
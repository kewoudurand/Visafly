<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'instructor_id',       // ← NEW : user avec rôle instructeur
        'titre', 'sous_titre', 'description',
        'niveau', 'couleur', 'icone',
        'duree_estimee_minutes',
        'gratuit', 'publie', 'ordre', 'slug',
        // Ajoute ici d'autres colonnes de ta table courses existante
    ];

    protected $casts = [
        'gratuit' => 'boolean',
        'publie'  => 'boolean',
    ];

    // ══════════════════════════════════════════════════════════════
    // Relations
    // ══════════════════════════════════════════════════════════════

    /** L'instructeur responsable de ce cours */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function lecons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'cours_id');
    }

    public function progressions(): HasMany
    {
        return $this->hasMany(CourseProgression::class, 'cours_id');
    }

    // ══════════════════════════════════════════════════════════════
    // Scopes
    // ══════════════════════════════════════════════════════════════

    public function scopePublies(Builder $query): Builder
    {
        return $query->where('publie', true);
    }

    /** Filtre les cours appartenant à un instructeur donné */
    public function scopeDeInstructeur(Builder $query, int $instructorId): Builder
    {
        return $query->where('instructor_id', $instructorId);
    }

    // ══════════════════════════════════════════════════════════════
    // Helpers — propriété / accès
    // ══════════════════════════════════════════════════════════════

    public function appartientA(int $userId): bool
    {
        return (int) $this->instructeur_id === $userId;
    }

    /**
     * Admin/super-admin → toujours autorisé.
     * Instructeur → seulement ses propres cours.
     */
    public function peutEtreGerePar(?int $userId = null): bool
    {
        $user = $userId ? User::find($userId) : auth()->user();
        if (! $user) return false;
        if ($user->hasAnyRole(['admin', 'super-admin'])) return true;
        return $this->appartientA($user->id);
    }

    // ══════════════════════════════════════════════════════════════
    // Helpers — divers
    // ══════════════════════════════════════════════════════════════

    public function progressionDe(?int $userId = null): ?CourseProgression
    {
        $uid = $userId ?? auth()->id();
        return $this->progressions()->where('user_id', $uid)->first();
    }

    public function nomInstructeur(): string
    {
        return $this->instructor?->name ?? 'Non assigné';
    }
}
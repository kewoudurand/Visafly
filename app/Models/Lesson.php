<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    protected $fillable = [
        'cours_id',
        'instructor_id',       // ← user avec rôle instructeur
        'titre', 'slug', 'type', 'contenu',
        'mots', 'exercices',
        'fichier_audio', 'transcription_audio', 'questions_audio',
        'gratuite', 'publiee', 'ordre',
        'points_recompense', 'duree_estimee_minutes',
    ];

    protected $casts = [
        'mots'            => 'array',
        'exercices'       => 'array',
        'questions_audio' => 'array',
        'gratuite'        => 'boolean',
        'publiee'         => 'boolean',
    ];

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'cours_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function progressions(): HasMany
    {
        return $this->hasMany(LessonProgression::class, 'lesson_id');
    }

    public function scopePubliees(Builder $query): Builder
    {
        return $query->where('publiee', true);
    }

    public function scopeOrdonnees(Builder $query): Builder
    {
        return $query->orderBy('ordre');
    }

    public function scopeDeInstructeur(Builder $query, int $instructorId): Builder
    {
        return $query->where('instructor_id', $instructorId);
    }

    public function appartientA(int $userId): bool
    {
        return (int) $this->instructor_id === $userId;
    }

    public function peutEtreGereePar(?int $userId = null): bool
    {
        $user = $userId ? User::find($userId) : auth()->user();
        if (! $user) return false;
        if ($user->hasAnyRole(['admin', 'super-admin'])) return true;
        return $this->appartientA($user->id);
    }

    public function progressionDe(?int $userId = null): ?LessonProgression
    {
        $uid = $userId ?? auth()->id();
        return $this->progressions()->where('user_id', $uid)->first();
    }

    public function estTermineePar($user): bool
    {
        // Si on nous passe l'objet User, on récupère son ID
        $userId = ($user instanceof \App\Models\User) ? $user->id : $user;

        if (!$userId) return false;

        return \DB::table('user_lesson_progress')
            ->where('lesson_id', $this->id)
            ->where('user_id', $userId)
            ->where('terminee', true)
            ->exists();
    }

    public function estAccessiblePar(?int $userId = null): bool
    {
        if ($this->gratuite) return true;
        $uid = $userId ?? auth()->id();
        return \App\Models\LangueAbonnement::where('user_id', $uid)
            ->where('langue_id', $this->cours->langue_id ?? null)
            ->where('statut', 'actif')
            ->where('date_fin', '>=', now())
            ->exists();
    }

    public function nombreMots(): int { return count($this->mots ?? []); }
    public function nombreExercices(): int { return count($this->exercices ?? []); }

    public function urlAudio(): ?string
    {
        if (!$this->fichier_audio) return null;
        
        // Storage::url() ajoutera automatiquement '/storage/' devant le chemin 
        // et utilisera l'APP_URL de votre fichier .env
        return Storage::disk('public')->url($this->fichier_audio);
    }
    
    // ── MÉTHODE mimeTypeAudio() NOUVELLE ────────────────────────
    
    public function mimeTypeAudio(): string
    {
        if (! $this->fichier_audio) return 'audio/mpeg';
    
        return match (strtolower(pathinfo($this->fichier_audio, PATHINFO_EXTENSION))) {
            'mp3'  => 'audio/mpeg',
            'wav'  => 'audio/wav',
            'ogg'  => 'audio/ogg',
            'm4a'  => 'audio/mp4',
            'aac'  => 'audio/aac',
            'webm' => 'audio/webm',
            default => 'audio/mpeg',
        };
    }
    public function nomInstructeur(): string
    {
        return $this->instructeur?->name ?? 'Non assigné';
    }

    public function iconeType(): string
    {
        return match ($this->type) {
            'vocabulaire' => 'bi-alphabet',
            'dialogue'    => 'bi-chat-dots',
            'grammaire'   => 'bi-pencil-square',
            'audio'       => 'bi-headphones',
            'lecture'     => 'bi-book',
            default       => 'bi-journals',
        };
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'vocabulaire' => 'success',
            'dialogue'    => 'primary',
            'grammaire'   => 'warning',
            'audio'       => 'info',
            'lecture'     => 'secondary',
            default       => 'dark',
        };
    }
}
<?php
// app/Models/Lesson.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $table = 'lessons';

    protected $fillable = [
        'cours_id', 'titre', 'slug', 'contenu', 'type',
        'mots', 'exercices', 'audio',
        'duree_minutes', 'gratuite', 'ordre', 'points_recompense',
    ];

    protected $casts = [
        'mots'      => 'array',
        'exercices' => 'array',
        'gratuite'  => 'boolean',
    ];

    // ── Relations ──

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'cours_id');
    }

    public function progres(): HasMany
    {
        return $this->hasMany(CoursProgres::class, 'lesson_id');
    }

    // ── Helpers ──

    public function estTermineePar(int $userId): bool
    {
        return CoursProgres::where('user_id', $userId)
                           ->where('lesson_id', $this->id)
                           ->where('statut', 'termine')
                           ->exists();
    }

    public function typeIcon(): string
    {
        return match($this->type) {
            'vocabulaire'   => 'bi-alphabet',
            'grammaire'     => 'bi-pencil-square',
            'dialogue'      => 'bi-chat-dots',
            'exercice'      => 'bi-check2-circle',
            'culture'       => 'bi-globe-europe-africa',
            'prononciation' => 'bi-mic',
            default         => 'bi-book',
        };
    }

    public function typeLabel(): string
    {
        return match($this->type) {
            'vocabulaire'   => 'Vocabulaire',
            'grammaire'     => 'Grammaire',
            'dialogue'      => 'Dialogue',
            'exercice'      => 'Exercice',
            'culture'       => 'Culture',
            'prononciation' => 'Prononciation',
            default         => ucfirst($this->type),
        };
    }
}
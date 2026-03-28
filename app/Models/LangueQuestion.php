<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LangueQuestion extends Model
{
    protected $table = 'langue_questions';

    protected $fillable = [
        'serie_id', 'enonce', 'type_question',
        'image', 'audio', 'contexte',
        'points', 'duree_secondes', 'explication', 'ordre',
    ];

    // ── Relations ──

    public function serie(): BelongsTo
    {
        return $this->belongsTo(LangueSerie::class, 'serie_id');
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(LangueReponse::class, 'question_id')->orderBy('ordre');
    }

    // ── Helpers ──

    public function typeLabel(): string
    {
        return match($this->type_question) {
            'qcm'         => 'QCM',
            'vrai_faux'   => 'Vrai / Faux',
            'texte_libre' => 'Réponse libre',
            'audio'       => 'Réponse audio',
            default       => ucfirst($this->type_question),
        };
    }

    public function bonneReponse(): ?LangueReponse
    {
        return $this->reponses->firstWhere('correcte', true);
    }
}
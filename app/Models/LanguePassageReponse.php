<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguePassageReponse extends Model
{
    protected $table = 'langue_passages_reponses';

    protected $fillable = [
        'passage_id',
        'question_id',
        'reponse_donnee',
        'correcte',
    ];

    protected $casts = [
        'correcte' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Le passage auquel cette réponse appartient
     */
    public function passage(): BelongsTo
    {
        return $this->belongsTo(LanguePassage::class, 'passage_id');
    }

    /**
     * La question répondue
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(LangueQuestion::class, 'question_id');
    }

    // Scopes

    /**
     * Obtenir les réponses correctes
     */
    public function scopeCorrectes($query)
    {
        return $query->where('correcte', true);
    }

    /**
     * Obtenir les réponses incorrectes
     */
    public function scopeIncorrectes($query)
    {
        return $query->where('correcte', false);
    }

    /**
     * Obtenir les réponses d'un passage
     */
    public function scopeForPassage($query, $passageId)
    {
        return $query->where('passage_id', $passageId);
    }
}
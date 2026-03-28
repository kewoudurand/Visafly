<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LangueReponse extends Model
{
    protected $table = 'langue_reponses';

    protected $fillable = [
        'question_id', 'texte', 'correcte', 'ordre',
    ];

    protected $casts = [
        'correcte' => 'boolean',
    ];

    // ── Relation ──

    public function question(): BelongsTo
    {
        return $this->belongsTo(LangueQuestion::class, 'question_id');
    }
}
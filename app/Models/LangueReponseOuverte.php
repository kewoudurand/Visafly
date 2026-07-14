<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LangueReponseOuverte extends Model
{
    protected $table = 'langue_reponses_ouvertes';

    protected $fillable = [
        'question_id', 'user_id', 'passage_id',
        'reponse_texte', 'audio_path',
        'score', 'commentaire_correcteur', 'corrige_par', 'corrige_at',
    ];

    protected $casts = [
        'corrige_at' => 'datetime',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(LangueQuestion::class, 'question_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function correcteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'corrige_par');
    }
}
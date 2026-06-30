<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationNote extends Model
{
    protected $table = 'consultation_notes';

    protected $fillable = [
        'consultation_id',
        'auteur_id',
        'contenu',
        'visible_client',
        'pipeline_etape_id',
        'document_id',
    ];

    protected $casts = [
        'visible_client' => 'boolean',
    ];

    // ── Relations ────────────────────────────────────────────────────────

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    // Relation avec l'utilisateur (consultant) qui a écrit la note

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function etape(): BelongsTo
    {
        return $this->belongsTo(PipelineEtape::class, 'pipeline_etape_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
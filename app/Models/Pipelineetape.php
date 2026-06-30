<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ConsultationNote;

class PipelineEtape extends Model
{
    protected $table = 'pipeline_etapes';

    protected $fillable = [
        'consultation_id',
        'ordre',
        'titre',
        'description',
        'pays_cle',
        'statut',
        'validee_le',
        'documents_requis',
        'validee_par',
        'note_consultant',
    ];

    protected $casts = [
        'validee_le' => 'datetime',
        'documents_requis' => 'array',
    ];

    // ══════════════════════════════════════════════════════════════
    //  Relations
    // ══════════════════════════════════════════════════════════════

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function validePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    /** Documents liés à cette étape (via etape_index sur la table documents) */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'etape_index', 'ordre')
                    ->where('consultation_id', $this->consultation_id);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ConsultationNote::class, 'pipeline_etape_id');
    }

    // Dans PipelineEtape.php, ajouter cette méthode :
    public function getDocumentsRequisAttribute($value): array
    {
        if (is_array($value)) return $value;
        if (is_string($value)) return json_decode($value, true) ?? [];
        return [];
    }

    // ══════════════════════════════════════════════════════════════
    //  Helpers
    // ══════════════════════════════════════════════════════════════

    public function estValidee(): bool
    {
        return $this->statut === 'valide';
    }

    public function estActive(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Valider l'étape — appelé depuis le dashboard consultant.
     * Passe la suivante en "en_cours" et incrémente etape_courante
     * sur la consultation (ce qui déclenche l'Observer).
     */
    public function valider(int $consultantId, ?string $note = null): void
    {
        $this->update([
            'statut'          => 'valide',
            'validee_le'      => now(),
            'validee_par'     => $consultantId,
            'note_consultant' => $note,
        ]);

        $consultation  = $this->consultation;
        $prochainOrdre = $this->ordre + 1;

        // Activer l'étape suivante
        $etapeSuivante = $consultation->pipelineEtapes()
            ->where('ordre', $prochainOrdre)
            ->first();

        if ($etapeSuivante) {
            $etapeSuivante->update(['statut' => 'en_cours']);
        }

        // Incrémenter → déclenche ConsultationObserver::updated()
        // qui recalcule progression et envoie la notification
        $consultation->update(['etape_courante' => $prochainOrdre]);
    }

    /**
     * Rejeter l'étape avec un motif — notifie l'étudiant.
     * Utilise la nomenclature title/message de ta table notifications.
     */
    public function rejeter(int $consultantId, string $note): void
    {
        $this->update([
            'statut'          => 'rejete',
            'validee_par'     => $consultantId,
            'note_consultant' => $note,
        ]);

        // Repasser en "en_cours" pour que l'étudiant puisse re-soumettre
        $this->update(['statut' => 'en_cours']);

        Notification::consultation(
            $this->consultation,
            'etape_rejetee',
            "Action requise : {$this->titre}",
            "L'étape « {$this->titre} » nécessite une correction. Motif : {$note}",
            ['screen' => 'pipeline', 'etape_index' => $this->ordre],
            "/etudiant/visa/{$this->consultation_id}",
            'Corriger mon dossier'
        );
    }
}
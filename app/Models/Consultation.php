<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PipelineEtape;
use App\Models\RendezVous;
use App\Models\Notification;
use App\Models\ConsultationNote;
use App\Support\PipelineConfig;

class Consultation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'user_id',
        'consultant_id',
        'full_name',
        'birth_date',
        'nationality',
        'residence_country',
        'phone',
        'email',
        'project_type',
        'destination_country',
        'metadata',
        'status',
        'etape_courante',
        'progression',
        'is_urgent',
        'note_admin',
        'motif_declin',
        'date_confirmee',
        'duree_minutes',
        'canal',
        'lien_visio',
        'montant_total',
        'devise',
    ];

    /**
     * Typage automatique des attributs (Casting).
     */
    protected $casts = [
        'birth_date' => 'date',
        'date_confirmee' => 'datetime',
        'is_urgent' => 'boolean',
        'duree_minutes' => 'integer',
        'metadata' => 'array', // 💡 Transforme le JSON en tableau array PHP automatiquement !
        'progression' => 'float',
    ];

    /**
     * Relation : La consultation appartient à un candidat (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation : La consultation est gérée par un consultant (User).
     */
    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    /**
     * Relation : Les documents physiques associés à cette procédure.
     * (Idéal si tu as une table "documents" ou "attachments")
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class ,'consultation_id'); 
        // Ou via une table polymorphique si tu as un système global de fichiers
    }

    public function getNumeroDossierAttribute()
    {
        $annee = $this->created_at->format('Y');
        $mois = $this->created_at->format('m');
        $id = str_pad($this->id, 4, '0', STR_PAD_LEFT);
        
        return "VF-{$annee}{$mois}-{$id}";
    }


    public function projetLabel(): string
    {
        return match($this->project_type) {
            'etudes'       => 'Études à l\'étranger',
            'travail'      => 'Travail / Emploi',
            'immigration'  => 'Immigration permanente',
            'visa'         => 'Visa court séjour',
            'bourse'       => 'Bourse d\'études',
            'regroupement' => 'Regroupement familial',
            default        => $this->project_type ?? '—',
        };
    }

    public function statutLabel(): string
    {
        return match($this->status) {
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours de traitement',
            'approuvee'  => 'Approuvée',
            'declinee'   => 'Déclinée',
            'terminee'   => 'Terminée',
            default      => ucfirst($this->status ?? 'Non défini'),
        };
    }

    public function statutClass(): string
    {
        return 'bs-' . $this->statut;
    }

    public function canalLabel(): string
    {
        return match($this->canal) {
            'video'      => 'Vidéoconférence',
            'telephone'  => 'Téléphone',
            'presentiel' => 'Présentiel',
            default      => 'Vidéoconférence',
        };
    }

    public function canalIcon(): string
    {
        return match($this->canal) {
            'video'      => 'bi-camera-video',
            'telephone'  => 'bi-telephone',
            'presentiel' => 'bi-building',
            default      => 'bi-camera-video',
        };
    }

    // Nom & email unifiés (connecté ou anonyme)
    public function getClientNameAttribute(): string  { return $this->user?->name  ?? $this->full_name ?? 'Anonyme'; }
    public function getClientEmailAttribute(): string { return $this->user?->email ?? $this->email     ?? '—'; }

    // ── Relations paiements────────────────────────────────────────────────────
    
    public function paiements()
    {
        return $this->hasMany(\App\Models\ConsultationPaiement::class, 'consultation_id');
    }
    
    // Si pas déjà présente (relation pipeline)
    public function pipelineEtapes()
    {
        return $this->hasMany(PipelineEtape::class, 'consultation_id')->orderBy('ordre');
    }
 
    // ── Accesseurs utiles ────────────────────────────────────
    
    public function getTotalPayeAttribute(): float
    {
        return $this->paiements->where('statut', 'recu')->sum('montant');
    }
    
    public function getResteAPayerAttribute(): float
    {
        return max(0, ($this->montant_total ?? 0) - $this->total_paye);
    }
    
    public function getPourcentagePayeAttribute(): int
    {
        if (!$this->montant_total || $this->montant_total <= 0) return 0;
        return min(100, (int) round(($this->total_paye / $this->montant_total) * 100));
    }

    public function peutEtreTraitee(): bool
    {
        return in_array($this->statut, ['en_attente', 'en_cours']);
    }

    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class)->orderBy('date_heure');
    }
    
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
    
    public function notes(): HasMany
    {
        return $this->hasMany(ConsultationNote::class);
    }
    
    /** Notes visibles par le client */
    public function notesClient(): HasMany
    {
        return $this->hasMany(ConsultationNote::class)->where('visible_client', true);
    }
    
    // ── Helpers ──────────────────────────────────────────────────────
    
    /** Étape actuellement active */
    public function etapeActive(): ?PipelineEtape
    {
        return $this->pipelineEtapes()->where('statut', 'en_cours')->first();
    }
    
    /** Prochain rendez-vous à venir */
    public function prochainRendezVous(): ?RendezVous
    {
        return $this->rendezVous()
            ->where('date_heure', '>', now())
            ->where('statut', '!=', 'annule')
            ->orderBy('date_heure')
            ->first();
    }
    
    /** Nombre de notifications non lues pour ce dossier */
    public function nbNotificationsNonLues(): int
    {
        return $this->notifications()->whereNull('lu_le')->count();
    }
    
    // ── Réponse API enrichie (à utiliser dans ConsultationController@show) ──
    
    public function toApiDetail(): array
    {
        return [
            'id'                  => $this->id,
            'user_id'             => $this->user_id,
            'destination_country' => $this->destination_country,
            'project_type'        => $this->project_type,
            'status'              => $this->status,
            'etape_courante'      => $this->etape_courante,
            'progression'         => $this->progression,
            'note_admin'          => $this->note_admin,
            'motif_declin'        => $this->motif_declin,
            'date_confirmee'      => $this->date_confirmee,
            'metadata'            => $this->metadata,
    
            // Pipeline complète
            'pipeline_etapes'     => $this->pipelineEtapes->map(fn($e) => [
                'id'              => $e->id,
                'ordre'           => $e->ordre,
                'titre'           => $e->titre,
                'description'     => $e->description,
                'statut'          => $e->statut,
                'note_consultant' => $e->note_consultant,
                'validee_le'      => $e->validee_le?->toDateTimeString(),
            ]),
    
            // Prochain RDV
            'prochain_rdv'        => $this->prochainRendezVous() ? [
                'date_heure'   => $this->prochainRendezVous()->date_heure->toDateTimeString(),
                'canal'        => $this->prochainRendezVous()->canal,
                'lien_visio'   => $this->prochainRendezVous()->lien_visio,
                'statut'       => $this->prochainRendezVous()->statut,
            ] : null,
    
            // Notes visibles client
            'notes'               => $this->notesClient->map(fn($n) => [
                'contenu'    => $n->contenu,
                'created_at' => $n->created_at->toDateTimeString(),
            ]),
    
            'created_at'          => $this->created_at->toDateTimeString(),
            'updated_at'          => $this->updated_at->toDateTimeString(),
        ];
    }
}
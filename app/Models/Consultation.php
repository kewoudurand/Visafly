<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{

    protected $fillable = [
        'user_id','consultant_id',
        'full_name','birth_date','nationality','residence_country',
        'phone','email','profession',
        'project_type','destination_country',
        'visa_history','visa_history_details',
        'last_degree','graduation_year','field_of_study','language_level','work_experience',
        'passport_valid','documents_available','admission_or_contract','financial_proof',
        'budget','departure_date','referral_source','message','need_consultation',
        'status','statut','urgent','note_admin','motif_declin',
        'date_confirmee','duree_minutes','canal','lien_visio',
        'note_client','avis_client',
    ];

    protected $casts = [
        'birth_date'           => 'date',
        'date_confirmee'       => 'datetime',
        'visa_history'         => 'boolean',
        'passport_valid'       => 'boolean',
        'documents_available'  => 'boolean',
        'admission_or_contract'=> 'boolean',
        'financial_proof'      => 'boolean',
        'need_consultation'    => 'boolean',
        'urgent'               => 'boolean',
        'status'               => 'boolean',
    ];


    // ── Relations ──
    public function user(): BelongsTo  { return $this->belongsTo(User::class, 'user_id'); }
    public function consultant(): BelongsTo { return $this->belongsTo(User::class, 'consultant_id'); }

    // ── Scopes ──
    public function scopeEnAttente($q)  { return $q->where('statut','en_attente'); }
    public function scopeApprouvees($q) { return $q->where('statut','approuvee'); }
    public function scopeUrgentes($q)   { return $q->where('urgent',true); }

    // ── État ──
    public function peutEtreTraitee(): bool { return in_array($this->statut,['en_attente','en_cours']); }
    public function estApprouvee(): bool    { return $this->statut === 'approuvee'; }
    public function estTerminee(): bool     { return $this->statut === 'terminee'; }
    public function estDeclinee(): bool     { return $this->statut === 'declinee'; }

    // ── Labels ──
    public function statutLabel(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours d\'examen',
            'approuvee'  => 'Approuvée',
            'declinee'   => 'Déclinée',
            'annulee'    => 'Annulée',
            'terminee'   => 'Terminée',
            default      => ucfirst($this->statut ?? '—'),
        };
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
}
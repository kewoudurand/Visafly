<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LangueAbonnement extends Model
{
    protected $table = 'langue_abonnements';
    
    protected $fillable = [
        'user_id',
        'plan_id',
        'langue_id',
        'code',
        'montant',
        'devise',
        'debut_at',
        'fin_at',
        'actif',
        'reference_paiement',
        'methode_paiement',
        'statut_paiement',
    ];

    protected $casts = [
        'debut_at' => 'datetime',
        'fin_at' => 'datetime',
        'actif' => 'boolean',
    ];

    // ════════════════════════════════════════
    //  RELATIONS
    // ════════════════════════════════════════

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanAbonnement::class);
    }

    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class);
    }

    // ════════════════════════════════════════
    //  SCOPES
    // ════════════════════════════════════════

    public function scopeActifs($query)
    {
        return $query->where('actif', true)
                    ->where('fin_at', '>=', now());
    }

    public function scopeExpires($query)
    {
        return $query->where('fin_at', '<', now());
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut_paiement', 'en_attente');
    }

    public function scopeConfirmes($query)
    {
        return $query->where('statut_paiement', 'confirme');
    }

    // ════════════════════════════════════════
    //  STATIC HELPERS
    // ════════════════════════════════════════

    /**
     * Vérifier si un utilisateur a un abonnement actif
     */
    public static function userHasActifAbonnement(int $userId): bool
    {
        return static::where('user_id', $userId)
            ->actifs()
            ->exists();
    }

    /**
     * Obtenir l'abonnement actif d'un utilisateur
     */
    public static function getAbonnementActif(int $userId, ?int $langueId = null): ?self
    {
        $query = static::where('user_id', $userId)->actifs();
        
        if ($langueId) {
            $query->where('langue_id', $langueId);
        }
        
        return $query->latest('debut_at')->first();
    }

    // ════════════════════════════════════════
    //  INSTANCE HELPERS
    // ════════════════════════════════════════

    public function isActif(): bool
    {
        return $this->actif && $this->fin_at >= now();
    }

    public function isExpire(): bool
    {
        return $this->fin_at < now();
    }

    public function joursRestants(): int
    {
        if (!$this->isActif()) return 0;
        return $this->fin_at->diffInDays(now());
    }

    public function estBientotExpire(): bool
    {
        return $this->joursRestants() <= 7;
    }

    public function montantFormate(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' ' . $this->devise;
    }

    public function dureeJours(): int
    {
        return $this->plan->duree_jours ?? 0;
    }

    public function nomPlan(): string
    {
        return $this->plan->nom ?? 'Plan inconnu';
    }
}
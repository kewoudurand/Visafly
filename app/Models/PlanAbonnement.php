<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanAbonnement extends Model
{
    protected $table = 'plans_abonnements';

    protected $fillable = [
        'nom',
        'code',
        'couleur',
        'icone',
        'description',
        'prix',
        'devise',
        'duree_jours',
        'points',
        'populaire',
        'actif',
        'ordre',
    ];

    protected $casts = [
        'points'    => 'array',
        'populaire' => 'boolean',
        'actif'     => 'boolean',
    ];

    // ════════════════════════════════════════
    //  RELATIONS
    // ════════════════════════════════════════

    /**
     * ✅ CORRECT : relation vers LangueAbonnement (remplace TcfAbonnement)
     */
    public function abonnements(): HasMany
    {
        return $this->hasMany(LangueAbonnement::class, 'plan_id');
    }

    // ════════════════════════════════════════
    //  SCOPES
    // ════════════════════════════════════════

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopePopulaires($query)
    {
        return $query->where('populaire', true);
    }

    // ════════════════════════════════════════
    //  HELPERS
    // ════════════════════════════════════════

    public function prixFormate(): string
    {
        return number_format($this->prix, 0, ',', ' ') . ' ' . $this->devise;
    }

    /**
     * Obtenir les avantages formatés pour l'affichage
     */
    public function pointsFormates(): array
    {
        $points = $this->points ?? [];
        if (!is_array($points)) {
            return [];
        }
        return array_filter($points, fn($p) => !empty($p['texte'] ?? null));
    }

    /**
     * Vérifier si le plan est populaire
     */
    public function estPopulaire(): bool
    {
        return (bool) $this->populaire;
    }

    /**
     * Formater la durée du plan
     */
    public function dureeFormatee(): string
    {
        if ($this->duree_jours == 30) {
            return '1 mois';
        } elseif ($this->duree_jours == 365) {
            return '1 an';
        } elseif ($this->duree_jours % 30 == 0) {
            $mois = (int)($this->duree_jours / 30);
            return $mois . ' mois';
        } else {
            return $this->duree_jours . ' jours';
        }
    }

    /**
     * Nombre total d'abonnements actifs
     */
    public function nbAbonnementsActifs(): int
    {
        return $this->abonnements()
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->count();
    }

    /**
     * Revenus générés par ce plan
     */
    public function revenus(): float
    {
        return (float) $this->abonnements()
            ->where('statut_paiement', 'confirme')
            ->sum('montant');
    }
}
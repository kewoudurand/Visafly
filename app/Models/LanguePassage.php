<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguePassage extends Model
{
    protected $table = 'langue_passages';

    protected $fillable = [
        'user_id',
        'serie_id',
        'discipline_id',
        'langue_id', // ✅ NOUVEAU : lien direct vers la langue
        'statut',
        'score',
        'bonnes_reponses',
        'mauvaises_reponses',
        'non_repondues',
        'total_questions',
        'points_obtenus',
        'points_total',
        'debut_at',
        'fin_at',
        'duree_secondes',
    ];

    protected $casts = [
        'debut_at' => 'datetime',
        'fin_at'   => 'datetime',
    ];

    // ════════════════════════════════════════
    //  RELATIONS ✅ CORRIGÉES
    // ════════════════════════════════════════

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(LangueSerie::class, 'serie_id');
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(LangueDiscipline::class, 'discipline_id');
    }

    /**
     * ✅ CORRIGÉ : Relation directe vers Langue
     * (au lieu de la propriété calculée qui causait l'erreur)
     */
    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(LanguePassageReponse::class, 'passage_id');
    }

    // ════════════════════════════════════════
    //  SCOPES
    // ════════════════════════════════════════

    public function scopeTermines($q)
    {
        return $q->where('statut', 'termine');
    }

    public function scopeEnCours($q)
    {
        return $q->where('statut', 'en_cours');
    }

    public function scopePourLangue($q, string $code)
    {
        return $q->whereHas('langue', fn($l) => $l->where('code', $code));
    }

    public function scopePourUtilisateur($q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    // ════════════════════════════════════════
    //  HELPERS / FORMATTERS
    // ════════════════════════════════════════

    public function dureeFormatee(): string
    {
        if (!$this->duree_secondes) return '—';
        $m = floor($this->duree_secondes / 60);
        $s = $this->duree_secondes % 60;
        return sprintf("%dm %02ds", (int)$m, (int)$s);
    }

    public function scoreLabel(): string
    {
        if ($this->score === null) return '—';
        return (int)$this->score . '%';
    }

    public function scoreColor(): string
    {
        if ($this->score === null) return '#888';
        return match(true) {
            $this->score >= 80 => '#1B3A6B',  // Bleu foncé
            $this->score >= 60 => '#1cc88a',  // Vert
            $this->score >= 40 => '#F5A623',  // Orange
            default            => '#E24B4A',  // Rouge
        };
    }

    public function niveauAtteint(): string
    {
        if ($this->score === null) return '—';
        return match(true) {
            $this->score >= 80 => 'C1-C2',
            $this->score >= 60 => 'B2',
            $this->score >= 50 => 'B1',
            $this->score >= 40 => 'A2',
            default            => 'A1',
        };
    }

    public function tauxReussite(): float
    {
        if (!$this->total_questions || $this->total_questions == 0) {
            return 0;
        }
        return round(($this->bonnes_reponses / $this->total_questions) * 100, 2);
    }

    public function statsFormatees(): array
    {
        return [
            'bonnes'    => $this->bonnes_reponses ?? 0,
            'mauvaises' => $this->mauvaises_reponses ?? 0,
            'non_repondues' => $this->non_repondues ?? 0,
            'total'     => $this->total_questions ?? 0,
            'taux'      => $this->tauxReussite(),
        ];
    }
}
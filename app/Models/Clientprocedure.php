<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Procedure;

class ClientProcedure extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_procedures';

    protected $fillable = [
        'procedure_id',
        'user_id',
        'assigne_par',
        'prix_total',
        'devise',
        'statut',
        'date_debut',
        'note',
    ];

    protected $casts = [
        'prix_total' => 'decimal:2',
        'date_debut' => 'date',
    ];

    // ────────────────────────────────
    //  Relations
    // ────────────────────────────────
    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignePar()
    {
        return $this->belongsTo(User::class, 'assigne_par');
    }

    public function paiements()
    {
        return $this->hasMany(ProcedurePaiement::class);
    }

    // ────────────────────────────────
    //  Calculs financiers
    // ────────────────────────────────
    public function totalVerse(): float
    {
        return (float) $this->paiements()->where('statut', 'recu')->sum('montant');
    }

    public function resteAPayer(): float
    {
        return max(0, (float) $this->prix_total - $this->totalVerse());
    }

    public function pourcentagePaye(): int
    {
        if ((float) $this->prix_total <= 0) {
            return 0;
        }
        return (int) round(($this->totalVerse() / (float) $this->prix_total) * 100);
    }

    public function estSolde(): bool
    {
        return $this->resteAPayer() <= 0;
    }

    // ────────────────────────────────
    //  Helpers labels
    // ────────────────────────────────
    public function statutLabel(): string
    {
        return match ($this->statut) {
            'en_cours' => 'En cours',
            'terminee' => 'Terminée',
            'annulee'  => 'Annulée',
            default    => ucfirst($this->statut),
        };
    }

    public function statutClass(): string
    {
        return match ($this->statut) {
            'en_cours' => 'badge-attente',
            'terminee' => 'badge-recu',
            'annulee'  => 'badge-annule',
            default    => '',
        };
    }
}
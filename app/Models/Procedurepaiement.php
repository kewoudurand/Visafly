<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedurePaiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'procedure_paiements';

    protected $fillable = [
        'client_procedure_id',
        'montant',
        'devise',
        'nom_payeur',
        'enregistre_par',
        'mode',
        'statut',
        'reference',
        'date_paiement',
        'note',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant'       => 'decimal:2',
    ];

    // ────────────────────────────────
    //  Relations
    // ────────────────────────────────
    public function clientProcedure()
    {
        return $this->belongsTo(ClientProcedure::class);
    }

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    // ────────────────────────────────
    //  Helpers
    // ────────────────────────────────
    public function nomPayeurAffiche(): string
    {
        return $this->nom_payeur ?: $this->clientProcedure?->client?->name ?? '—';
    }

    public function statutLabel(): string
    {
        return match ($this->statut) {
            'recu'       => 'Reçu',
            'en_attente' => 'En attente',
            'annule'     => 'Annulé',
            default      => ucfirst($this->statut),
        };
    }

    public function statutClass(): string
    {
        return match ($this->statut) {
            'recu'       => 'badge-recu',
            'en_attente' => 'badge-attente',
            'annule'     => 'badge-annule',
            default      => '',
        };
    }

    public function modeLabel(): string
    {
        return match ($this->mode) {
            'especes'      => 'Espèces',
            'virement'     => 'Virement',
            'mobile_money' => 'Mobile Money',
            'carte'        => 'Carte bancaire',
            'autre'        => 'Autre',
            default        => ucfirst($this->mode),
        };
    }

    public function montantFormate(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' ' . $this->devise;
    }
}
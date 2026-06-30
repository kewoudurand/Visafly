<?php
// ══════════════════════════════════════════════════════════
//  app/Models/ConsultationPaiement.php
// ══════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ConsultationPaiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'consultation_paiements';

    protected $fillable = [
        'consultation_id',
        'enregistre_par',
        'montant',
        'devise',
        'statut',
        'mode',
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
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    // ────────────────────────────────
    //  Helpers labels
    // ────────────────────────────────
    public function statutLabel(): string
    {
        return match($this->statut) {
            'recu'       => 'Reçu',
            'en_attente' => 'En attente',
            'annule'     => 'Annulé',
            default      => ucfirst($this->statut),
        };
    }

    public function statutClass(): string
    {
        return match($this->statut) {
            'recu'       => 'badge-recu',
            'en_attente' => 'badge-attente',
            'annule'     => 'badge-annule',
            default      => '',
        };
    }

    public function modeLabel(): string
    {
        return match($this->mode) {
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
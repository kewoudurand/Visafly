<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends Model
{
    protected $table = 'rendez_vous';

    protected $fillable = [
        'consultation_id',
        'consultant_id',
        'date_heure',
        'duree_minutes',
        'canal',
        'lien_visio',
        'adresse',
        'statut',
        'motif_annulation',
        'compte_rendu',
        'rappel_envoye',
    ];

    protected $casts = [
        'date_heure'    => 'datetime',
        'rappel_envoye' => 'boolean',
    ];

    // ══════════════════════════════════════════════════════════════
    //  Relations
    // ══════════════════════════════════════════════════════════════

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    // ══════════════════════════════════════════════════════════════
    //  Factory — programme un RDV et notifie l'étudiant
    //  Utilise la nomenclature title/message de ta table notifications
    // ══════════════════════════════════════════════════════════════

    public static function programmer(
        Consultation $consultation,
        int          $consultantId,
        string       $dateHeure,
        string       $canal,
        ?string      $lienVisio = null,
        int          $duree = 45
    ): static {
        $rdv = static::create([
            'consultation_id' => $consultation->id,
            'consultant_id'   => $consultantId,
            'date_heure'      => $dateHeure,
            'duree_minutes'   => $duree,
            'canal'           => $canal,
            'lien_visio'      => $lienVisio,
            'statut'          => 'prevu',
        ]);

        $dateFormatee  = $rdv->date_heure->format('d/m/Y à H:i');
        $canalFormate  = ucfirst(str_replace('_', ' ', $canal));

        Notification::consultation(
            $consultation,
            'rdv_programme',
            '📅 Rendez-vous programmé',
            "Votre rendez-vous est fixé le {$dateFormatee} via {$canalFormate}.",
            ['screen' => 'rdv', 'rdv_id' => $rdv->id, 'lien_visio' => $lienVisio],
            $lienVisio,
            $lienVisio ? 'Rejoindre la réunion' : null
        );

        return $rdv;
    }

    // ──────────────────────────────────────────────────────────────
    //  Annulation
    // ──────────────────────────────────────────────────────────────

    public function annuler(string $motif): void
    {
        $this->update([
            'statut'           => 'annule',
            'motif_annulation' => $motif,
        ]);

        Notification::consultation(
            $this->consultation,
            'rdv_annule',
            '🚫 Rendez-vous annulé',
            "Votre rendez-vous du {$this->date_heure->format('d/m/Y')} a été annulé. Motif : {$motif}",
            ['screen' => 'rdv']
        );
    }

    // ──────────────────────────────────────────────────────────────
    //  Rappel (appelé par un job schedulé — ex: 24h avant)
    // ──────────────────────────────────────────────────────────────

    public function envoyerRappel(): void
    {
        if ($this->rappel_envoye || $this->statut === 'annule') return;

        Notification::consultation(
            $this->consultation,
            'rdv_rappel',
            '⏰ Rappel de rendez-vous',
            "Votre rendez-vous est demain le {$this->date_heure->format('d/m/Y à H:i')}. Préparez vos documents.",
            ['screen' => 'rdv', 'rdv_id' => $this->id],
            $this->lien_visio,
            $this->lien_visio ? 'Rejoindre' : null
        );

        $this->update(['rappel_envoye' => true]);
    }
}
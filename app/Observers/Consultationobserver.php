<?php

namespace App\Observers;

use App\Models\Consultation;
use App\Models\PipelineEtape;
use App\Models\Notification;
use App\Support\PipelineConfig;

class ConsultationObserver
{
    /**
     * Après création : génère automatiquement les étapes de pipeline.
     */
    public function created(Consultation $consultation): void
    {
        $this->genererPipeline($consultation);
    }

    /**
     * Après mise à jour : recalcule progression + notifie si etape_courante a changé.
     */
    public function updated(Consultation $consultation): void
    {
        if ($consultation->wasChanged('etape_courante')) {
            $this->recalculerProgression($consultation);
            $this->notifierAvancementEtape($consultation);
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Génération de la pipeline
    // ──────────────────────────────────────────────────────────────

    private function genererPipeline(Consultation $consultation): void
    {
        $etapes = PipelineConfig::pourPays($consultation->destination_country);

        foreach ($etapes as $ordre => $config) {
            PipelineEtape::create([
                'consultation_id' => $consultation->id,
                'ordre'           => $ordre,
                'titre'           => $config['titre'],
                'description'     => $config['description'],
                'pays_cle'        => $config['pays_cle'],
                'statut'          => $ordre === 0 ? 'en_cours' : 'en_attente',
            ]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Recalcul de la progression
    // ──────────────────────────────────────────────────────────────

    private function recalculerProgression(Consultation $consultation): void
    {
        $total    = $consultation->pipelineEtapes()->count();
        $validees = $consultation->pipelineEtapes()->where('statut', 'valide')->count();
        $progression = $total > 0 ? round($validees / $total, 2) : 0.00;

        // withoutEvents pour éviter la récursion
        Consultation::withoutEvents(fn() => $consultation->update([
            'progression' => $progression,
        ]));
    }

    // ──────────────────────────────────────────────────────────────
    //  Notification à l'étudiant — utilise ta nomenclature existante
    // ──────────────────────────────────────────────────────────────

    private function notifierAvancementEtape(Consultation $consultation): void
    {
        $etapeIndex  = $consultation->etape_courante;
        $total       = $consultation->pipelineEtapes()->count();
        $estDerniere = $etapeIndex >= $total;

        $etape = $consultation->pipelineEtapes()
            ->where('ordre', $etapeIndex)
            ->first();

        if ($estDerniere) {
            // ── Visa obtenu ─────────────────────────────────────
            Notification::consultation(
                $consultation,
                'visa_obtenu',
                '🏆 Félicitations ! Visa obtenu',
                'Votre procédure est complète. Votre visa a été accordé ! Bienvenue dans votre nouvelle aventure.',
                ['screen' => 'pipeline'],
                "/etudiant/visa/{$consultation->id}",
                'Voir mon dossier'
            );
        } elseif ($etape) {
            // ── Étape suivante démarrée ─────────────────────────
            Notification::consultation(
                $consultation,
                'etape_demarree',
                "Nouvelle étape : {$etape->titre}",
                "L'étape « {$etape->titre} » est maintenant active. Consultez votre pipeline pour soumettre les documents requis.",
                ['screen' => 'pipeline', 'etape_index' => $etapeIndex],
                "/etudiant/visa/{$consultation->id}",
                'Voir ma pipeline'
            );
        }
    }
}
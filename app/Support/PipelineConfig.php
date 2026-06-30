<?php

namespace App\Support;

/**
 * Configuration centralisée des pipelines par pays.
 *
 * Usage :
 *   $etapes = PipelineConfig::pourPays('Canada');
 *   $etapes = PipelineConfig::pourPays('allemagne');  // insensible à la casse
 */
class PipelineConfig
{
    // ──────────────────────────────────────────────────────────────────────
    //  Point d'entrée
    // ──────────────────────────────────────────────────────────────────────

    public static function pourPays(?string $pays): array
    {
        if (! $pays) return self::canada();

        $key = strtolower(trim($pays));

        return match (true) {
            str_contains($key, 'canada')    => self::canada(),
            str_contains($key, 'allemagne') => self::allemagne(),
            str_contains($key, 'france')    => self::france(),
            str_contains($key, 'belgique')  => self::belgique(),
            str_contains($key, 'australie') => self::australie(),
            default                         => self::canada(),
        };
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Canada — Résidence permanente (Express Entrée)
    // ──────────────────────────────────────────────────────────────────────

    private static function canada(): array
    {
        return [
            ['pays_cle' => 'canada', 'titre' => 'Consultation initiale',
             'description' => 'Soumission du formulaire et vérification de l\'éligibilité par le consultant.'],
            ['pays_cle' => 'canada', 'titre' => 'Vérification des documents d\'identité',
             'description' => 'Le consultant vérifie votre passeport, acte de naissance et casier judiciaire.'],
            ['pays_cle' => 'canada', 'titre' => 'Test de langue (IELTS / TEF Canada)',
             'description' => 'Soumettez vos résultats de test linguistique. Score minimum requis : 6.0.'],
            ['pays_cle' => 'canada', 'titre' => 'Évaluation des diplômes (ECA)',
             'description' => 'Faites évaluer vos diplômes par un organisme reconnu par IRCC.'],
            ['pays_cle' => 'canada', 'titre' => 'Création du profil Express Entrée',
             'description' => 'Création et soumission de votre profil dans le bassin Express Entrée d\'IRCC.'],
            ['pays_cle' => 'canada', 'titre' => 'Invitation & Demande de résidence permanente',
             'description' => 'Suite à l\'invitation à postuler (ITA), soumission du dossier complet de RP.'],
            ['pays_cle' => 'canada', 'titre' => 'Obtention du visa / Confirmation de RP',
             'description' => 'Votre dossier est approuvé. Vous obtenez la résidence permanente canadienne.'],
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Allemagne — Visa de travail
    // ──────────────────────────────────────────────────────────────────────

    private static function allemagne(): array
    {
        return [
            ['pays_cle' => 'allemagne', 'titre' => 'Consultation initiale',
             'description' => 'Soumission du formulaire et informations personnelles.'],
            ['pays_cle' => 'allemagne', 'titre' => 'Vérification d\'identité',
             'description' => 'Contrôle du passeport et de l\'acte de naissance traduit.'],
            ['pays_cle' => 'allemagne', 'titre' => 'Reconnaissance des diplômes',
             'description' => 'Équivalence de vos qualifications en Allemagne via l\'organisme compétent.'],
            ['pays_cle' => 'allemagne', 'titre' => 'Offre d\'emploi / Contrat de travail',
             'description' => 'Soumettez le contrat de travail signé par un employeur allemand.'],
            ['pays_cle' => 'allemagne', 'titre' => 'Test de langue (Goethe-Institut B1)',
             'description' => 'Soumettez votre certificat Goethe-Institut. Niveau B1 minimum requis.'],
            ['pays_cle' => 'allemagne', 'titre' => 'Demande de visa au consulat',
             'description' => 'Dépôt du dossier complet à l\'ambassade d\'Allemagne.'],
            ['pays_cle' => 'allemagne', 'titre' => 'Obtention du visa de travail',
             'description' => 'Visa approuvé. Votre aventure professionnelle en Allemagne commence.'],
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  France — Visa long séjour (VLS-TS)
    // ──────────────────────────────────────────────────────────────────────

    private static function france(): array
    {
        return [
            ['pays_cle' => 'france', 'titre' => 'Consultation initiale',
             'description' => 'Formulaire de consultation et informations personnelles.'],
            ['pays_cle' => 'france', 'titre' => 'Vérification des documents d\'identité',
             'description' => 'Contrôle du passeport biométrique et de l\'acte de naissance (traduction + apostille).'],
            ['pays_cle' => 'france', 'titre' => 'Justificatif de ressources financières',
             'description' => 'Relevés bancaires 3 mois et preuve de logement en France.'],
            ['pays_cle' => 'france', 'titre' => 'Diplômes et motif du séjour',
             'description' => 'Lettre d\'admission universitaire ou contrat de travail selon le motif.'],
            ['pays_cle' => 'france', 'titre' => 'Demande de visa VLS-TS',
             'description' => 'Dépôt du dossier via TLScontact ou VFS Global. Formulaire CERFA N°14571.'],
            ['pays_cle' => 'france', 'titre' => 'Validation OFII',
             'description' => 'Validation du visa auprès de l\'Office français de l\'immigration et de l\'intégration.'],
            ['pays_cle' => 'france', 'titre' => 'Obtention du titre de séjour',
             'description' => 'Titre de séjour accordé. Bienvenue en France !'],
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Belgique — Visa type D
    // ──────────────────────────────────────────────────────────────────────

    private static function belgique(): array
    {
        return [
            ['pays_cle' => 'belgique', 'titre' => 'Consultation initiale',
             'description' => 'Soumission du formulaire de consultation.'],
            ['pays_cle' => 'belgique', 'titre' => 'Vérification des documents d\'identité',
             'description' => 'Passeport valide (12 mois min.) et acte de naissance légalisé.'],
            ['pays_cle' => 'belgique', 'titre' => 'Justificatif de séjour',
             'description' => 'Lettre d\'invitation de l\'employeur belge ou lettre d\'admission d\'un établissement.'],
            ['pays_cle' => 'belgique', 'titre' => 'Preuve de moyens financiers',
             'description' => 'Relevés bancaires 3 mois et garantie financière du garant si nécessaire.'],
            ['pays_cle' => 'belgique', 'titre' => 'Demande de visa au consulat',
             'description' => 'Dépôt du dossier complet à l\'ambassade de Belgique.'],
            ['pays_cle' => 'belgique', 'titre' => 'Approbation SPF Affaires étrangères',
             'description' => 'Validation par le Service Public Fédéral belge.'],
            ['pays_cle' => 'belgique', 'titre' => 'Obtention du visa D',
             'description' => 'Visa accordé. Votre nouvelle vie en Belgique commence.'],
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Australie — Visa compétences (subclass 189/190)
    // ──────────────────────────────────────────────────────────────────────

    private static function australie(): array
    {
        return [
            ['pays_cle' => 'australie', 'titre' => 'Consultation initiale',
             'description' => 'Formulaire de consultation et informations personnelles.'],
            ['pays_cle' => 'australie', 'titre' => 'Évaluation des compétences (Skills Assessment)',
             'description' => 'Évaluation par l\'organisme compétent pour votre profession.'],
            ['pays_cle' => 'australie', 'titre' => 'Test de langue (IELTS / PTE)',
             'description' => 'Résultats du test d\'anglais. Score minimum 6.0 par composante.'],
            ['pays_cle' => 'australie', 'titre' => 'Expression d\'intérêt (EOI — SkillSelect)',
             'description' => 'Dépôt de votre Expression of Interest via le portail SkillSelect.'],
            ['pays_cle' => 'australie', 'titre' => 'Invitation à postuler',
             'description' => 'Réception de l\'invitation officielle par le Department of Home Affairs.'],
            ['pays_cle' => 'australie', 'titre' => 'Demande de visa officielle (ImmiAccount)',
             'description' => 'Soumission du dossier complet : formulaire 47SK, bilan médical, casier judiciaire, assurance OVHC.'],
            ['pays_cle' => 'australie', 'titre' => 'Obtention du visa (subclass 189 / 190)',
             'description' => 'Visa approuvé. L\'Australie vous attend.'],
        ];
    }
}
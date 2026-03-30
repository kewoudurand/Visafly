<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\PlanAbonnement;

class PlanAbonnementSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'nom'         => 'Mensuel',
                'code'        => 'mensuel',
                'couleur'     => '#1B3A6B',
                'icone'       => 'bi-calendar-month',
                'description' => 'Idéal pour une préparation courte',
                'prix'        => 5000,
                'devise'      => 'XAF',
                'duree_jours' => 30,
                'populaire'   => false,
                'actif'       => true,
                'ordre'       => 1,
                'points'      => [
                    ['icone' => 'bi-check-circle-fill', 'couleur' => '#1cc88a', 'texte' => 'Accès à toutes les séries TCF/TEF'],
                    ['icone' => 'bi-check-circle-fill', 'couleur' => '#1cc88a', 'texte' => 'Correction automatique illimitée'],
                    ['icone' => 'bi-check-circle-fill', 'couleur' => '#1cc88a', 'texte' => 'Statistiques de progression'],
                    ['icone' => 'bi-x-circle-fill',    'couleur' => '#E24B4A', 'texte' => 'IELTS et Goethe non inclus'],
                    ['icone' => 'bi-x-circle-fill',    'couleur' => '#E24B4A', 'texte' => 'Support prioritaire non inclus'],
                ],
            ],
            [
                'nom'         => 'Trimestriel',
                'code'        => 'trimestriel',
                'couleur'     => '#F5A623',
                'icone'       => 'bi-calendar3',
                'description' => 'Le plus populaire — 3 mois de préparation',
                'prix'        => 12000,
                'devise'      => 'XAF',
                'duree_jours' => 90,
                'populaire'   => true,
                'actif'       => true,
                'ordre'       => 2,
                'points'      => [
                    ['icone' => 'bi-check-circle-fill',  'couleur' => '#1cc88a', 'texte' => 'Accès à tous les examens (TCF, TEF, IELTS, Goethe)'],
                    ['icone' => 'bi-check-circle-fill',  'couleur' => '#1cc88a', 'texte' => 'Correction automatique illimitée'],
                    ['icone' => 'bi-check-circle-fill',  'couleur' => '#1cc88a', 'texte' => 'Statistiques avancées'],
                    ['icone' => 'bi-check-circle-fill',  'couleur' => '#1cc88a', 'texte' => 'Téléchargement des résultats PDF'],
                    ['icone' => 'bi-lightning-charge-fill','couleur' => '#F5A623', 'texte' => 'Économisez 20% vs mensuel'],
                ],
            ],
            [
                'nom'         => 'Annuel',
                'code'        => 'annuel',
                'couleur'     => '#1B3A6B',
                'icone'       => 'bi-calendar-check',
                'description' => 'Accès complet toute l\'année',
                'prix'        => 40000,
                'devise'      => 'XAF',
                'duree_jours' => 365,
                'populaire'   => false,
                'actif'       => true,
                'ordre'       => 3,
                'points'      => [
                    ['icone' => 'bi-star-fill',           'couleur' => '#F5A623', 'texte' => 'Accès illimité à tous les examens'],
                    ['icone' => 'bi-star-fill',           'couleur' => '#F5A623', 'texte' => 'Support prioritaire par email'],
                    ['icone' => 'bi-check-circle-fill',   'couleur' => '#1cc88a', 'texte' => 'Statistiques et analyses avancées'],
                    ['icone' => 'bi-check-circle-fill',   'couleur' => '#1cc88a', 'texte' => 'Consultation offerte (1 par an)'],
                    ['icone' => 'bi-lightning-charge-fill','couleur' => '#F5A623', 'texte' => 'Économisez 33% vs mensuel'],
                ],
            ],
        ];

        foreach ($plans as $p) {
            PlanAbonnement::firstOrCreate(['code' => $p['code']], $p);
        }
    }
}
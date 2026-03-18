<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TcfController extends Controller
{
    // Page de sélection des épreuves
    public function index()
    {
        $epreuves = [
            [
                'slug'        => 'comprehension-ecrite',
                'titre'       => 'Compréhension Écrite',
                'icon'        => 'bi-file-text',
                'couleur'     => 'primary',
                'duree'       => '60 minutes',
                'questions'   => '29 questions',
                'description' => 'Lisez des textes variés et répondez à des questions de compréhension à choix multiples.',
                'conseils'    => ['Lire la question avant le texte', 'Repérer les mots-clés', 'Éliminer les mauvaises réponses'],
            ],
            [
                'slug'        => 'comprehension-orale',
                'titre'       => 'Compréhension Orale',
                'icon'        => 'bi-headphones',
                'couleur'     => 'success',
                'duree'       => '25 minutes',
                'questions'   => '29 questions',
                'description' => 'Écoutez des enregistrements audio et répondez à des questions de compréhension.',
                'conseils'    => ['Lire les options avant d\'écouter', 'Prendre des notes', 'Repérer les chiffres et dates'],
            ],
            [
                'slug'        => 'expression-ecrite',
                'titre'       => 'Expression Écrite',
                'icon'        => 'bi-pencil-square',
                'couleur'     => 'warning',
                'duree'       => '60 minutes',
                'questions'   => '3 tâches',
                'description' => 'Rédigez des textes courts et longs selon des consignes précises (courriel, lettre, essai).',
                'conseils'    => ['Respecter le nombre de mots', 'Structurer votre texte', 'Soigner la grammaire'],
            ],
            [
                'slug'        => 'expression-orale',
                'titre'       => 'Expression Orale',
                'icon'        => 'bi-mic',
                'couleur'     => 'danger',
                'duree'       => '12 minutes',
                'questions'   => '3 tâches',
                'description' => 'Exprimez-vous oralement à partir de documents déclencheurs (photo, texte court).',
                'conseils'    => ['Parler sans interruption', 'Utiliser un vocabulaire varié', 'Soigner la prononciation'],
            ],
            [
                'slug'        => 'maitrise-structures',
                'titre'       => 'Maîtrise des Structures',
                'icon'        => 'bi-diagram-3',
                'couleur'     => 'info',
                'duree'       => '30 minutes',
                'questions'   => '20 questions (optionnel)',
                'description' => 'Épreuve optionnelle testant votre connaissance des structures grammaticales du français.',
                'conseils'    => ['Revoir les temps verbaux', 'Maîtriser les accords', 'Connaître les connecteurs'],
            ],
        ];

        return view('tcf.index', compact('epreuves'));
    }

    // Page d'une épreuve spécifique
    public function epreuve($type)
    {
        $epreuves = [
            'comprehension-ecrite'  => 'Compréhension Écrite',
            'comprehension-orale'   => 'Compréhension Orale',
            'expression-ecrite'     => 'Expression Écrite',
            'expression-orale'      => 'Expression Orale',
            'maitrise-structures'   => 'Maîtrise des Structures',
        ];

        if (!array_key_exists($type, $epreuves)) {
            abort(404);
        }

        return view('tcf.epreuve', [
            'type'  => $type,
            'titre' => $epreuves[$type],
        ]);
    }
}
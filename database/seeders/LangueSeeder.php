<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Langue;
use App\Models\LangueDiscipline;
use App\Models\LangueSerie;
use App\Models\LangueQuestion;
use App\Models\LangueReponse;

// ─────────────────────────────────────────────────────────────
//  FICHIER : database/seeders/LangueSeeder.php
//  Lance   : php artisan db:seed --class=LangueSeeder
// ─────────────────────────────────────────────────────────────

class LangueSeeder extends Seeder
{
    public function run(): void
    {
        // ════════════════════════════════════════════
        //  1. TCF CANADA
        // ════════════════════════════════════════════
        $tcf = Langue::firstOrCreate(['code' => 'tcf'], [
            'nom'         => 'TCF Canada',
            'organisme'   => 'France Éducation International',
            'description' => 'Test de Connaissance du Français pour le Canada. Reconnu par Immigration, Réfugiés et Citoyenneté Canada (IRCC).',
            'couleur'     => '#F5A623',
            'actif'       => true,
            'ordre'       => 1,
        ]);

        $tcfDisciplines = [
            ['code'=>'ce',  'nom'=>'Compréhension de l\'Écrit',  'nom_court'=>'CE',  'type'=>'texte',      'has_image'=>true,  'has_audio'=>false, 'duree_minutes'=>60, 'ordre'=>1, 'consigne'=>'Lisez attentivement chaque texte et répondez aux questions qui suivent.'],
            ['code'=>'co',  'nom'=>'Compréhension de l\'Oral',   'nom_court'=>'CO',  'type'=>'audio',      'has_image'=>true,  'has_audio'=>true,  'duree_minutes'=>40, 'ordre'=>2, 'consigne'=>'Écoutez attentivement les documents sonores et répondez aux questions.'],
            ['code'=>'ee',  'nom'=>'Expression Écrite',          'nom_court'=>'EE',  'type'=>'production', 'has_image'=>true,  'has_audio'=>false, 'duree_minutes'=>60, 'ordre'=>3, 'consigne'=>'Rédigez des textes en réponse aux sujets proposés.'],
            ['code'=>'eo',  'nom'=>'Expression Orale',           'nom_court'=>'EO',  'type'=>'production', 'has_image'=>true,  'has_audio'=>true,  'duree_minutes'=>15, 'ordre'=>4, 'consigne'=>'Exprimez-vous oralement à partir des documents proposés.'],
        ];

        foreach ($tcfDisciplines as $d) {
            $disc = LangueDiscipline::firstOrCreate(
                ['langue_id' => $tcf->id, 'code' => $d['code']],
                array_merge($d, ['langue_id' => $tcf->id, 'actif' => true])
            );

            // Série 100 gratuite
            $serie100 = LangueSerie::firstOrCreate(
                ['discipline_id' => $disc->id, 'titre' => 'Série 100'],
                [
                    'description'   => 'Série d\'entraînement gratuite — niveau intermédiaire',
                    'niveau'        => 2,
                    'duree_minutes' => $d['duree_minutes'],
                    'gratuite'      => true,
                    'active'        => true,
                    'ordre'         => 1,
                ]
            );
            $this->seedQuestionsExemple($serie100, $disc);

            // Séries 148-153 premium
            foreach (range(148, 153) as $i => $num) {
                $serie = LangueSerie::firstOrCreate(
                    ['discipline_id' => $disc->id, 'titre' => "Série {$num}"],
                    [
                        'description'   => "Série d'entraînement {$num} — nouveau format",
                        'niveau'        => $num <= 150 ? 2 : 3,
                        'duree_minutes' => $d['duree_minutes'],
                        'gratuite'      => $num <= 149, // 148 et 149 gratuites
                        'active'        => true,
                        'ordre'         => $i + 2,
                    ]
                );
                $this->seedQuestionsExemple($serie, $disc);
            }
        }

        // ════════════════════════════════════════════
        //  2. TEF CANADA
        // ════════════════════════════════════════════
        $tef = Langue::firstOrCreate(['code' => 'tef'], [
            'nom'         => 'TEF Canada',
            'organisme'   => 'CCI Paris Île-de-France',
            'description' => 'Test d\'Évaluation de Français pour le Canada. Reconnu par IRCC pour l\'immigration permanente.',
            'couleur'     => '#F5A623',
            'actif'       => true,
            'ordre'       => 2,
        ]);

        $tefDisciplines = [
            ['code'=>'ce', 'nom'=>'Compréhension de l\'Écrit',   'nom_court'=>'CE', 'type'=>'texte',      'has_image'=>true,  'has_audio'=>false, 'duree_minutes'=>60, 'ordre'=>1, 'consigne'=>'Lisez les textes et répondez aux questions.'],
            ['code'=>'co', 'nom'=>'Compréhension de l\'Oral',    'nom_court'=>'CO', 'type'=>'audio',      'has_image'=>false, 'has_audio'=>true,  'duree_minutes'=>40, 'ordre'=>2, 'consigne'=>'Écoutez les enregistrements et répondez.'],
            ['code'=>'pe', 'nom'=>'Production Écrite',            'nom_court'=>'PE', 'type'=>'production', 'has_image'=>true,  'has_audio'=>false, 'duree_minutes'=>60, 'ordre'=>3, 'consigne'=>'Rédigez des textes sur les sujets proposés.'],
            ['code'=>'po', 'nom'=>'Production Orale',             'nom_court'=>'PO', 'type'=>'production', 'has_image'=>true,  'has_audio'=>true,  'duree_minutes'=>15, 'ordre'=>4, 'consigne'=>'Exprimez-vous oralement à partir des documents.'],
        ];

        foreach ($tefDisciplines as $d) {
            $disc = LangueDiscipline::firstOrCreate(
                ['langue_id' => $tef->id, 'code' => $d['code']],
                array_merge($d, ['langue_id' => $tef->id, 'actif' => true])
            );

            foreach (['Série 1', 'Série 2', 'Série 3'] as $i => $titre) {
                $serie = LangueSerie::firstOrCreate(
                    ['discipline_id' => $disc->id, 'titre' => $titre],
                    [
                        'description'   => "{$titre} TEF Canada",
                        'niveau'        => $i + 1,
                        'duree_minutes' => $d['duree_minutes'],
                        'gratuite'      => $i === 0,
                        'active'        => true,
                        'ordre'         => $i + 1,
                    ]
                );
                $this->seedQuestionsExemple($serie, $disc);
            }
        }

        // ════════════════════════════════════════════
        //  3. IELTS
        // ════════════════════════════════════════════
        $ielts = Langue::firstOrCreate(['code' => 'ielts'], [
            'nom'         => 'IELTS',
            'organisme'   => 'British Council / IDP',
            'description' => 'International English Language Testing System. Reconnu mondialement pour l\'immigration et les études.',
            'couleur'     => '#1B3A6B',
            'actif'       => true,
            'ordre'       => 3,
        ]);

        $ieltsDisciplines = [
            ['code'=>'reading',   'nom'=>'Reading',   'nom_court'=>'RD', 'type'=>'texte',      'has_image'=>true,  'has_audio'=>false, 'duree_minutes'=>60, 'ordre'=>1, 'consigne'=>'Read the passages and answer the questions.'],
            ['code'=>'listening', 'nom'=>'Listening', 'nom_court'=>'LT', 'type'=>'audio',      'has_image'=>false, 'has_audio'=>true,  'duree_minutes'=>40, 'ordre'=>2, 'consigne'=>'Listen carefully and answer the questions.'],
            ['code'=>'writing',   'nom'=>'Writing',   'nom_court'=>'WT', 'type'=>'production', 'has_image'=>true,  'has_audio'=>false, 'duree_minutes'=>60, 'ordre'=>3, 'consigne'=>'Write your answers to the tasks provided.'],
            ['code'=>'speaking',  'nom'=>'Speaking',  'nom_court'=>'SP', 'type'=>'production', 'has_image'=>true,  'has_audio'=>true,  'duree_minutes'=>15, 'ordre'=>4, 'consigne'=>'Respond to the questions and prompts.'],
        ];

        foreach ($ieltsDisciplines as $d) {
            $disc = LangueDiscipline::firstOrCreate(
                ['langue_id' => $ielts->id, 'code' => $d['code']],
                array_merge($d, ['langue_id' => $ielts->id, 'actif' => true])
            );

            foreach (['Practice Test 1', 'Practice Test 2'] as $i => $titre) {
                $serie = LangueSerie::firstOrCreate(
                    ['discipline_id' => $disc->id, 'titre' => $titre],
                    [
                        'description'   => "{$titre} — IELTS Academic",
                        'niveau'        => $i + 2,
                        'duree_minutes' => $d['duree_minutes'],
                        'gratuite'      => $i === 0,
                        'active'        => true,
                        'ordre'         => $i + 1,
                    ]
                );
                $this->seedQuestionsExemple($serie, $disc);
            }
        }

        // ════════════════════════════════════════════
        //  4. GOETHE-ZERTIFIKAT
        // ════════════════════════════════════════════
        $goethe = Langue::firstOrCreate(['code' => 'goethe'], [
            'nom'         => 'Goethe-Zertifikat',
            'organisme'   => 'Goethe-Institut',
            'description' => 'Examen officiel de langue allemande du Goethe-Institut. Reconnu dans le monde entier.',
            'couleur'     => '#1B3A6B',
            'actif'       => true,
            'ordre'       => 4,
        ]);

        $goetheDisciplines = [
            ['code'=>'lesen',     'nom'=>'Lesen (Lecture)',              'nom_court'=>'LE', 'type'=>'texte',      'has_image'=>true,  'has_audio'=>false, 'duree_minutes'=>65, 'ordre'=>1, 'consigne'=>'Lesen Sie die Texte und beantworten Sie die Fragen.'],
            ['code'=>'horen',     'nom'=>'Hören (Compréhension Orale)',  'nom_court'=>'HO', 'type'=>'audio',      'has_image'=>false, 'has_audio'=>true,  'duree_minutes'=>40, 'ordre'=>2, 'consigne'=>'Hören Sie die Texte und beantworten Sie die Fragen.'],
            ['code'=>'schreiben', 'nom'=>'Schreiben (Expression Écrite)','nom_court'=>'SC', 'type'=>'production', 'has_image'=>true,  'has_audio'=>false, 'duree_minutes'=>75, 'ordre'=>3, 'consigne'=>'Schreiben Sie Texte zu den vorgegebenen Themen.'],
            ['code'=>'sprechen',  'nom'=>'Sprechen (Expression Orale)',  'nom_court'=>'SP', 'type'=>'production', 'has_image'=>true,  'has_audio'=>true,  'duree_minutes'=>15, 'ordre'=>4, 'consigne'=>'Sprechen Sie zu den vorgegebenen Themen.'],
        ];

        foreach ($goetheDisciplines as $d) {
            $disc = LangueDiscipline::firstOrCreate(
                ['langue_id' => $goethe->id, 'code' => $d['code']],
                array_merge($d, ['langue_id' => $goethe->id, 'actif' => true])
            );

            foreach (['Übungstest 1', 'Übungstest 2'] as $i => $titre) {
                $serie = LangueSerie::firstOrCreate(
                    ['discipline_id' => $disc->id, 'titre' => $titre],
                    [
                        'description'   => "{$titre} — Goethe B2",
                        'niveau'        => 2,
                        'duree_minutes' => $d['duree_minutes'],
                        'gratuite'      => $i === 0,
                        'active'        => true,
                        'ordre'         => $i + 1,
                    ]
                );
                $this->seedQuestionsExemple($serie, $disc);
            }
        }
    }

    // ════════════════════════════════════════════
    //  Crée 5 questions QCM exemples par série
    // ════════════════════════════════════════════
    private function seedQuestionsExemple(LangueSerie $serie, LangueDiscipline $disc): void
    {
        // Ne pas recréer si déjà des questions
        if ($serie->questions()->count() > 0) return;

        $questionsData = match($disc->code) {
            'ce', 'reading', 'lesen' => $this->questionsCE(),
            'co', 'listening', 'horen' => $this->questionsCO(),
            'ee', 'pe', 'writing', 'schreiben' => $this->questionsEE(),
            default => $this->questionsEO(),
        };

        foreach ($questionsData as $idx => $qData) {
            $q = LangueQuestion::create([
                'serie_id'       => $serie->id,
                'enonce'         => $qData['enonce'],
                'type_question'  => 'qcm',
                'contexte'       => $qData['contexte'] ?? null,
                'points'         => 1,
                'duree_secondes' => 60,
                'explication'    => $qData['explication'] ?? null,
                'ordre'          => $idx,
            ]);

            foreach ($qData['reponses'] as $ri => $rep) {
                LangueReponse::create([
                    'question_id' => $q->id,
                    'texte'       => $rep['texte'],
                    'correcte'    => $rep['correcte'],
                    'ordre'       => $ri,
                ]);
            }
        }

        // Mettre à jour le compteur
        $serie->update(['nombre_questions' => $serie->questions()->count()]);
    }

    private function questionsCE(): array
    {
        return [
            [
                'contexte' => "La ville de Lyon est connue pour sa gastronomie et son architecture Renaissance. Classée au patrimoine mondial de l'UNESCO, elle attire chaque année des millions de touristes.",
                'enonce'   => "D'après le texte, pourquoi Lyon est-elle classée au patrimoine mondial de l'UNESCO ?",
                'explication' => "Le texte mentionne l'architecture Renaissance comme raison du classement.",
                'reponses' => [
                    ['texte' => 'Pour sa gastronomie réputée',         'correcte' => false],
                    ['texte' => 'Pour son architecture Renaissance',    'correcte' => true],
                    ['texte' => 'Pour le nombre de ses touristes',      'correcte' => false],
                    ['texte' => 'Pour ses musées',                      'correcte' => false],
                ],
            ],
            [
                'contexte' => "Le télétravail s'est généralisé depuis 2020. Selon une étude, 65% des salariés préfèrent désormais travailler depuis chez eux au moins deux jours par semaine.",
                'enonce'   => "Quel pourcentage de salariés préfère le télétravail au moins deux jours par semaine ?",
                'explication' => "L'étude citée indique 65% des salariés.",
                'reponses' => [
                    ['texte' => '45%', 'correcte' => false],
                    ['texte' => '55%', 'correcte' => false],
                    ['texte' => '65%', 'correcte' => true],
                    ['texte' => '75%', 'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Le mot « bénévole » signifie :",
                'reponses' => [
                    ['texte' => 'Quelqu\'un qui travaille contre rémunération', 'correcte' => false],
                    ['texte' => 'Quelqu\'un qui travaille sans être payé',      'correcte' => true],
                    ['texte' => 'Quelqu\'un qui est en vacances',               'correcte' => false],
                    ['texte' => 'Un employé à temps partiel',                   'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Laquelle de ces phrases est correctement écrite ?",
                'reponses' => [
                    ['texte' => 'Il faut que tu vient demain.',     'correcte' => false],
                    ['texte' => 'Il faut que tu viennes demain.',   'correcte' => true],
                    ['texte' => 'Il faut que tu venais demain.',    'correcte' => false],
                    ['texte' => 'Il faut que tu viendrais demain.', 'correcte' => false],
                ],
                'explication' => "Après « il faut que », on utilise le subjonctif présent : « viennes ».",
            ],
            [
                'enonce'   => "Quel est le synonyme du mot « rapide » ?",
                'reponses' => [
                    ['texte' => 'Lent',    'correcte' => false],
                    ['texte' => 'Vif',     'correcte' => true],
                    ['texte' => 'Lourd',   'correcte' => false],
                    ['texte' => 'Calme',   'correcte' => false],
                ],
            ],
        ];
    }

    private function questionsCO(): array
    {
        return [
            [
                'enonce'   => "D'après le document sonore, quelle est la destination principale du vol ?",
                'contexte' => "[Audio] Annonce d'aéroport : embarquement immédiat pour le vol AF1234 à destination de Montréal, porte 42.",
                'reponses' => [
                    ['texte' => 'Paris',    'correcte' => false],
                    ['texte' => 'Montréal', 'correcte' => true],
                    ['texte' => 'Genève',   'correcte' => false],
                    ['texte' => 'Lyon',     'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Selon le dialogue, que cherche la personne ?",
                'contexte' => "[Audio] — Excusez-moi, je cherche la bibliothèque municipale. — Elle est à deux rues d'ici, sur la droite.",
                'reponses' => [
                    ['texte' => 'La mairie',                 'correcte' => false],
                    ['texte' => 'La bibliothèque municipale','correcte' => true],
                    ['texte' => 'La gare',                   'correcte' => false],
                    ['texte' => 'Le marché',                 'correcte' => false],
                ],
            ],
            [
                'enonce'   => "D'après l'enregistrement, à quelle heure commence la réunion ?",
                'contexte' => "[Audio] Bonjour, je vous rappelle que la réunion de direction est fixée à 14h30 ce jeudi.",
                'reponses' => [
                    ['texte' => '13h00', 'correcte' => false],
                    ['texte' => '14h00', 'correcte' => false],
                    ['texte' => '14h30', 'correcte' => true],
                    ['texte' => '15h00', 'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Quel temps fait-il selon le bulletin météo ?",
                'contexte' => "[Audio] Pour ce week-end, attendez-vous à un temps pluvieux sur toute la région parisienne avec des températures autour de 12 degrés.",
                'reponses' => [
                    ['texte' => 'Ensoleillé', 'correcte' => false],
                    ['texte' => 'Neigeux',    'correcte' => false],
                    ['texte' => 'Pluvieux',   'correcte' => true],
                    ['texte' => 'Venteux',    'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Que recommande le médecin au patient ?",
                'contexte' => "[Audio] — Vous devez vous reposer et boire beaucoup d'eau. Évitez les activités sportives pendant une semaine.",
                'reponses' => [
                    ['texte' => 'Faire du sport tous les jours', 'correcte' => false],
                    ['texte' => 'Prendre des médicaments forts', 'correcte' => false],
                    ['texte' => 'Se reposer et boire de l\'eau', 'correcte' => true],
                    ['texte' => 'Travailler normalement',        'correcte' => false],
                ],
            ],
        ];
    }

    private function questionsEE(): array
    {
        return [
            [
                'enonce'   => "Dans un email formel, comment débute-t-on la salutation ?",
                'reponses' => [
                    ['texte' => 'Salut,',                'correcte' => false],
                    ['texte' => 'Coucou,',               'correcte' => false],
                    ['texte' => 'Madame, Monsieur,',     'correcte' => true],
                    ['texte' => 'Hey,',                  'correcte' => false],
                ],
                'explication' => "Dans un email formel, on utilise « Madame, Monsieur, » comme salutation.",
            ],
            [
                'enonce'   => "Quelle formule de politesse convient pour terminer une lettre de candidature ?",
                'reponses' => [
                    ['texte' => 'Bisous',                                                              'correcte' => false],
                    ['texte' => 'À bientôt',                                                           'correcte' => false],
                    ['texte' => 'Veuillez agréer, Madame, Monsieur, mes salutations distinguées.',     'correcte' => true],
                    ['texte' => 'Cordialement à vous',                                                 'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Quel connecteur logique exprime la conséquence ?",
                'reponses' => [
                    ['texte' => 'Cependant', 'correcte' => false],
                    ['texte' => 'Donc',      'correcte' => true],
                    ['texte' => 'Bien que',  'correcte' => false],
                    ['texte' => 'Pourtant',  'correcte' => false],
                ],
                'explication' => "« Donc » exprime la conséquence logique.",
            ],
            [
                'enonce'   => "Dans un texte argumentatif, qu'est-ce qu'un argument ?",
                'reponses' => [
                    ['texte' => 'Une histoire inventée',                   'correcte' => false],
                    ['texte' => 'Une raison qui soutient une idée principale', 'correcte' => true],
                    ['texte' => 'Une conclusion sans justification',        'correcte' => false],
                    ['texte' => 'Un titre accrocheur',                     'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Comment reformuler « Il est nécessaire de partir tôt » ?",
                'reponses' => [
                    ['texte' => 'Il ne faut pas partir tôt.',     'correcte' => false],
                    ['texte' => 'Il est inutile de partir tôt.',  'correcte' => false],
                    ['texte' => 'Il faut partir tôt.',            'correcte' => true],
                    ['texte' => 'Il est possible de partir tard.','correcte' => false],
                ],
            ],
        ];
    }

    private function questionsEO(): array
    {
        return [
            [
                'enonce'   => "Comment réagissez-vous quand quelqu'un vous présente une personne ?",
                'reponses' => [
                    ['texte' => 'Je dis rien.',                            'correcte' => false],
                    ['texte' => 'Enchanté(e), comment allez-vous ?',       'correcte' => true],
                    ['texte' => 'Bonjour, je m\'appelle Marie et toi ?',   'correcte' => false],
                    ['texte' => 'Je tourne le dos.',                       'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Pour décrire une image montrant des personnes au travail, vous dites :",
                'reponses' => [
                    ['texte' => 'Je vois rien de spécial.',                          'correcte' => false],
                    ['texte' => 'Cette image représente des professionnels en réunion.', 'correcte' => true],
                    ['texte' => 'C\'est une photo floue.',                           'correcte' => false],
                    ['texte' => 'Je ne sais pas quoi dire.',                         'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Pour donner votre opinion, quelle expression est la plus appropriée ?",
                'reponses' => [
                    ['texte' => 'J\'ai aucune opinion.',             'correcte' => false],
                    ['texte' => 'À mon avis, il faudrait…',          'correcte' => true],
                    ['texte' => 'C\'est nul.',                       'correcte' => false],
                    ['texte' => 'Je sais pas trop.',                 'correcte' => false],
                ],
            ],
            [
                'enonce'   => "Comment demander poliment à quelqu'un de répéter ?",
                'reponses' => [
                    ['texte' => 'Répète !',                                              'correcte' => false],
                    ['texte' => 'Quoi ?',                                                'correcte' => false],
                    ['texte' => 'Pourriez-vous répéter, s\'il vous plaît ?',             'correcte' => true],
                    ['texte' => 'Je n\'entends pas.',                                    'correcte' => false],
                ],
                'explication' => "La formule polie avec le conditionnel est la plus appropriée.",
            ],
            [
                'enonce'   => "Pour conclure une présentation orale, quelle phrase convient ?",
                'reponses' => [
                    ['texte' => 'Voilà, c\'est tout.',                                    'correcte' => false],
                    ['texte' => 'Pour conclure, je dirais que cette problématique mérite notre attention.', 'correcte' => true],
                    ['texte' => 'J\'ai fini.',                                            'correcte' => false],
                    ['texte' => 'C\'est la fin.',                                         'correcte' => false],
                ],
            ],
        ];
    }
}
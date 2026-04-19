<?php
// database/seeders/CoursAllemandSeeder.php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CoursAllemandSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::first()->id ?? 1;
        
        $this->seedNiveauA1();
        $this->seedNiveauA2();
        $this->seedNiveauB1();
    }

    // ════════════════ NIVEAU A1 ════════════════

    private function seedNiveauA1(): void
    {
        $cours = Course::firstOrCreate(['slug' => 'allemand-a1'], [
            'titre'        => 'Allemand A1 — Débutant',
            'sous_titre'   => 'Les bases de l\'allemand en 20 leçons',
            'description'  => 'Commencez votre voyage en allemand. Apprenez les salutations, les chiffres, les couleurs et les bases de la communication quotidienne.',
            'niveau'       => 'A1',
            'couleur'      => '#1cc88a',
            'icone'        => 'bi-1-circle',
            'duree_estimee_minutes' => 8,
            'gratuit'      => false,
            'publie'        => true,
            'ordre'        => 1,
        ]);

        $lecons = [
            // ── Leçon 1 ──
            [
                'titre'   => 'Les salutations — Grüße',
                'slug'    => 'a1-salutations',
                'type'    => 'vocabulaire',
                'contenu' => "# Les salutations en allemand\n\nEn allemand, les salutations varient selon le moment de la journée et le niveau de formalité...",
                'gratuite'=> true,
                'ordre'   => 1,
                'mots'    => [
                    ['de' => 'Hallo',             'fr' => 'Bonjour / Salut',     'phonetique' => 'ˈhalo',       'exemple' => 'Hallo, wie geht es Ihnen?'],
                    ['de' => 'Guten Morgen',      'fr' => 'Bonjour (matin)',     'phonetique' => 'ˈɡuːtən ˈmɔʁɡən','exemple' => 'Guten Morgen! Wie geht\'s?'],
                    ['de' => 'Guten Tag',         'fr' => 'Bonjour (journée)',   'phonetique' => 'ˈɡuːtən taːk','exemple' => 'Guten Tag, Herr Müller.'],
                    ['de' => 'Guten Abend',       'fr' => 'Bonsoir',             'phonetique' => 'ˈɡuːtən ˈaːbənt','exemple' => 'Guten Abend! Wie war Ihr Tag?'],
                    ['de' => 'Auf Wiedersehen',   'fr' => 'Au revoir',           'phonetique' => 'auf ˈviːdɐˌzeːən','exemple' => 'Auf Wiedersehen! Bis morgen.'],
                    ['de' => 'Tschüss',           'fr' => 'Salut / Ciao',        'phonetique' => 'tʃʏs',        'exemple' => 'Tschüss! Bis später.'],
                    ['de' => 'Gute Nacht',        'fr' => 'Bonne nuit',          'phonetique' => 'ˈɡuːtə naxt', 'exemple' => 'Gute Nacht und schlaf gut!'],
                    ['de' => 'Wie geht es Ihnen?','fr' => 'Comment allez-vous ?','phonetique' => 'viː ɡeːt ɛs ˈiːnən','exemple' => 'Guten Tag! Wie geht es Ihnen?'],
                    ['de' => 'Mir geht es gut',   'fr' => 'Je vais bien',        'phonetique' => 'miːɐ̯ ɡeːt ɛs ɡuːt','exemple' => '— Wie geht\'s? — Mir geht es gut, danke!'],
                    ['de' => 'Danke',             'fr' => 'Merci',               'phonetique' => 'ˈdaŋkə',      'exemple' => 'Danke schön!'],
                    ['de' => 'Bitte',             'fr' => 'S\'il vous plaît / De rien','phonetique' => 'ˈbɪtə','exemple' => 'Bitte, nehmen Sie Platz.'],
                    ['de' => 'Entschuldigung',    'fr' => 'Excusez-moi / Pardon','phonetique' => 'ɛntˈʃʊldɪɡʊŋ','exemple' => 'Entschuldigung, wo ist der Bahnhof?'],
                ],
                'exercices' => [
                    [
                        'question'    => 'Comment dit-on "Bonjour" le matin en allemand ?',
                        'type'        => 'qcm',
                        'choix'       => ['Guten Abend', 'Guten Morgen', 'Auf Wiedersehen', 'Gute Nacht'],
                        'reponse'     => 'Guten Morgen',
                        'explication' => '"Guten Morgen" signifie "Bonjour" spécifiquement le matin.',
                    ],
                    [
                        'question'    => 'Quelle phrase utilisez-vous pour dire "Au revoir" formellement ?',
                        'type'        => 'qcm',
                        'choix'       => ['Tschüss', 'Hallo', 'Auf Wiedersehen', 'Danke'],
                        'reponse'     => 'Auf Wiedersehen',
                        'explication' => '"Auf Wiedersehen" est le au revoir formel, "Tschüss" est informel.',
                    ],
                    [
                        'question'    => 'Traduisez : "Merci beaucoup"',
                        'type'        => 'texte_libre',
                        'reponse'     => 'Danke schön',
                        'explication' => '"Danke schön" = Merci beaucoup. "Danke" seul = Merci.',
                    ],
                    [
                        'question'    => 'Comment répondre à "Wie geht es Ihnen?" (Je vais bien) ?',
                        'type'        => 'qcm',
                        'choix'       => ['Guten Tag', 'Mir geht es gut', 'Bitte', 'Auf Wiedersehen'],
                        'reponse'     => 'Mir geht es gut',
                        'explication' => '"Mir geht es gut" = Je vais bien. Vous pouvez ajouter "danke!" = merci!',
                    ],
                    [
                        'question'    => 'Que signifie "Entschuldigung" ?',
                        'type'        => 'qcm',
                        'choix'       => ['Bonsoir', 'Merci', 'Excusez-moi', 'Bonne nuit'],
                        'reponse'     => 'Excusez-moi',
                        'explication' => '"Entschuldigung" sert à s\'excuser ou attirer l\'attention.',
                    ],
                ],
                'points_recompense' => 15,
            ],

            // ── Leçon 2 ──
            [
                'titre'   => 'Se présenter — Sich vorstellen',
                'slug'    => 'a1-se-presenter',
                'type'    => 'dialogue',
                'gratuite'=> true,
                'ordre'   => 2,
                'contenu' => "# Se présenter en allemand\n\nApprendre à se présenter est la première étape pour communiquer en allemand.",
                'mots'    => [
                    ['de' => 'Ich heiße...',       'fr' => 'Je m\'appelle...',   'phonetique' => 'ɪç ˈhaɪsə',   'exemple' => 'Ich heiße Marie. Und Sie?'],
                    ['de' => 'Mein Name ist...',   'fr' => 'Mon nom est...',     'phonetique' => 'maɪn ˈnaːmə ɪst','exemple' => 'Mein Name ist Hans Müller.'],
                    ['de' => 'Ich komme aus...',   'fr' => 'Je viens de...',     'phonetique' => 'ɪç ˈkɔmə aʊs', 'exemple' => 'Ich komme aus Frankreich.'],
                    ['de' => 'Ich wohne in...',    'fr' => 'J\'habite à...',     'phonetique' => 'ɪç ˈvoːnə ɪn', 'exemple' => 'Ich wohne in Berlin.'],
                    ['de' => 'Ich bin ... Jahre alt','fr' => 'J\'ai ... ans',    'phonetique' => 'ɪç bɪn ... ˈjaːʁə alt','exemple' => 'Ich bin 25 Jahre alt.'],
                    ['de' => 'Ich spreche...',     'fr' => 'Je parle...',        'phonetique' => 'ɪç ˈʃpʁɛçə',  'exemple' => 'Ich spreche Französisch und Deutsch.'],
                    ['de' => 'Ich lerne Deutsch',  'fr' => 'J\'apprends l\'allemand','phonetique' => 'ɪç ˈlɛʁnə dɔɪ̯tʃ','exemple' => 'Ich lerne Deutsch seit 3 Monaten.'],
                    ['de' => 'Freut mich',         'fr' => 'Enchanté(e)',        'phonetique' => 'fʁɔɪ̯t mɪç',   'exemple' => '— Ich heiße Anna. — Freut mich!'],
                    ['de' => 'Woher kommen Sie?',  'fr' => 'D\'où venez-vous ?', 'phonetique' => 'voˈheːɐ̯ ˈkɔmən ziː','exemple' => 'Woher kommen Sie? Ich komme aus Afrika.'],
                    ['de' => 'Wie alt sind Sie?',  'fr' => 'Quel âge avez-vous ?','phonetique' => 'viː alt zɪnt ziː','exemple' => 'Wie alt sind Sie? Ich bin 30 Jahre alt.'],
                ],
                'exercices' => [
                    [
                        'question'    => 'Complétez : "Ich _____ Marie." (Je m\'appelle Marie)',
                        'type'        => 'texte_libre',
                        'reponse'     => 'heiße',
                        'explication' => '"Ich heiße" = Je m\'appelle. Heiße vient du verbe "heißen".',
                    ],
                    [
                        'question'    => 'Comment dire "Je viens de Cameroun" ?',
                        'type'        => 'texte_libre',
                        'reponse'     => 'Ich komme aus Kamerun',
                        'explication' => '"Ich komme aus" + pays. Kamerun = Cameroun en allemand.',
                    ],
                    [
                        'question'    => 'Comment dit-on "Enchanté" ?',
                        'type'        => 'qcm',
                        'choix'       => ['Auf Wiedersehen', 'Freut mich', 'Ich heiße', 'Danke'],
                        'reponse'     => 'Freut mich',
                        'explication' => '"Freut mich" = Enchanté(e), ravi de vous rencontrer.',
                    ],
                    [
                        'question'    => 'Traduisez : "J\'apprends l\'allemand"',
                        'type'        => 'texte_libre',
                        'reponse'     => 'Ich lerne Deutsch',
                        'explication' => 'Lerne vient du verbe "lernen" (apprendre). Deutsch = allemand.',
                    ],
                ],
                'points_recompense' => 15,
            ],

            // ── Leçon 3 ──
            [
                'titre'   => 'Les chiffres — Zahlen (1–100)',
                'slug'    => 'a1-chiffres',
                'type'    => 'vocabulaire',
                'gratuite'=> false,
                'ordre'   => 3,
                'contenu' => "# Les chiffres en allemand\n\nLes chiffres sont fondamentaux pour parler des prix, de l'heure et des dates.",
                'mots'    => [
                    ['de' => 'null',          'fr' => '0',    'phonetique' => 'nʊl'],
                    ['de' => 'eins',          'fr' => '1',    'phonetique' => 'aɪns'],
                    ['de' => 'zwei',          'fr' => '2',    'phonetique' => 'tsvaɪ'],
                    ['de' => 'drei',          'fr' => '3',    'phonetique' => 'dʁaɪ'],
                    ['de' => 'vier',          'fr' => '4',    'phonetique' => 'fiːɐ̯'],
                    ['de' => 'fünf',          'fr' => '5',    'phonetique' => 'fʏnf'],
                    ['de' => 'sechs',         'fr' => '6',    'phonetique' => 'zɛks'],
                    ['de' => 'sieben',        'fr' => '7',    'phonetique' => 'ˈziːbən'],
                    ['de' => 'acht',          'fr' => '8',    'phonetique' => 'axt'],
                    ['de' => 'neun',          'fr' => '9',    'phonetique' => 'nɔɪ̯n'],
                    ['de' => 'zehn',          'fr' => '10',   'phonetique' => 'tseːn'],
                    ['de' => 'zwanzig',       'fr' => '20',   'phonetique' => 'ˈtsvantsɪç'],
                    ['de' => 'dreißig',       'fr' => '30',   'phonetique' => 'ˈdʁaɪ̯sɪç'],
                    ['de' => 'vierzig',       'fr' => '40',   'phonetique' => 'ˈfɪʁtsɪç'],
                    ['de' => 'fünfzig',       'fr' => '50',   'phonetique' => 'ˈfʏnftsɪç'],
                    ['de' => 'hundert',       'fr' => '100',  'phonetique' => 'ˈhʊndɐt'],
                    ['de' => 'tausend',       'fr' => '1000', 'phonetique' => 'ˈtaʊzənt'],
                ],
                'exercices' => [
                    [
                        'question'    => 'Comment dit-on "cinq" en allemand ?',
                        'type'        => 'qcm',
                        'choix'       => ['vier', 'fünf', 'sechs', 'sieben'],
                        'reponse'     => 'fünf',
                        'explication' => '"fünf" = 5. Notez le tréma sur le ü, qui change la prononciation.',
                    ],
                    [
                        'question'    => 'Quel chiffre est "neun" ?',
                        'type'        => 'qcm',
                        'choix'       => ['7', '8', '9', '10'],
                        'reponse'     => '9',
                        'explication' => '"neun" = 9. "zehn" = 10, "acht" = 8.',
                    ],
                    [
                        'question'    => 'Comment dit-on "vingt" ?',
                        'type'        => 'texte_libre',
                        'reponse'     => 'zwanzig',
                        'explication' => '"zwanzig" = 20. Les dizaines en -zig : zwanzig, dreißig, vierzig...',
                    ],
                    [
                        'question'    => 'Calculez : "drei + vier = ?" en allemand',
                        'type'        => 'texte_libre',
                        'reponse'     => 'sieben',
                        'explication' => '3 + 4 = 7. "drei" + "vier" = "sieben".',
                    ],
                ],
                'points_recompense' => 10,
            ],

            // ── Leçon 4 ──
            [
                'titre'   => 'Grammaire : Le genre des noms — Der, Die, Das',
                'slug'    => 'a1-genre-noms',
                'type'    => 'grammaire',
                'gratuite'=> false,
                'ordre'   => 4,
                'contenu' => "# Le genre des noms en allemand\n\nEn allemand, chaque nom a un genre : masculin (der), féminin (die) ou neutre (das). C'est l'une des particularités les plus importantes de l'allemand.",
                'mots'    => [
                    ['de' => 'der Mann',     'fr' => 'l\'homme',         'phonetique' => 'deːɐ man',         'exemple' => 'Der Mann ist groß.'],
                    ['de' => 'der Vater',    'fr' => 'le père',           'phonetique' => 'deːɐ ˈfaːtɐ',     'exemple' => 'Mein Vater heißt Karl.'],
                    ['de' => 'der Bruder',   'fr' => 'le frère',          'phonetique' => 'deːɐ ˈbʁuːdɐ',    'exemple' => 'Mein Bruder ist 20 Jahre alt.'],
                    ['de' => 'die Frau',     'fr' => 'la femme',          'phonetique' => 'diː fʁaʊ',         'exemple' => 'Die Frau arbeitet hier.'],
                    ['de' => 'die Mutter',   'fr' => 'la mère',           'phonetique' => 'diː ˈmʊtɐ',       'exemple' => 'Meine Mutter kommt aus Paris.'],
                    ['de' => 'die Schwester','fr' => 'la sœur',           'phonetique' => 'diː ˈʃvɛstɐ',     'exemple' => 'Meine Schwester heißt Sarah.'],
                    ['de' => 'das Kind',     'fr' => 'l\'enfant',         'phonetique' => 'das kɪnt',         'exemple' => 'Das Kind spielt im Garten.'],
                    ['de' => 'das Buch',     'fr' => 'le livre',          'phonetique' => 'das buːx',         'exemple' => 'Das Buch ist interessant.'],
                    ['de' => 'das Haus',     'fr' => 'la maison',         'phonetique' => 'das haʊs',         'exemple' => 'Das Haus ist groß und schön.'],
                    ['de' => 'die Stadt',    'fr' => 'la ville',          'phonetique' => 'diː ʃtat',         'exemple' => 'Berlin ist eine schöne Stadt.'],
                ],
                'exercices' => [
                    [
                        'question'    => 'Quel article va avec "Buch" (livre) ?',
                        'type'        => 'qcm',
                        'choix'       => ['der', 'die', 'das'],
                        'reponse'     => 'das',
                        'explication' => '"das Buch" — Les objets neutres utilisent "das". Il faut mémoriser le genre avec chaque mot.',
                    ],
                    [
                        'question'    => 'Quel est l\'article de "Frau" (femme) ?',
                        'type'        => 'qcm',
                        'choix'       => ['der', 'die', 'das'],
                        'reponse'     => 'die',
                        'explication' => '"die Frau" — Les personnes féminines ont généralement l\'article "die".',
                    ],
                    [
                        'question'    => 'Complétez : "___ Mann ist groß." (L\'homme est grand)',
                        'type'        => 'texte_libre',
                        'reponse'     => 'Der',
                        'explication' => '"Der Mann" — masculin. Der / die / das correspondent au, la, le.',
                    ],
                    [
                        'question'    => 'Quel est l\'article de "Kind" (enfant) ?',
                        'type'        => 'qcm',
                        'choix'       => ['der', 'die', 'das'],
                        'reponse'     => 'das',
                        'explication' => '"das Kind" est neutre. En allemand, le genre grammatical ne correspond pas toujours au genre naturel.',
                    ],
                ],
                'points_recompense' => 20,
            ],

            // ── Leçon 5 ──
            [
                'titre'   => 'Les couleurs — Farben',
                'slug'    => 'a1-couleurs',
                'type'    => 'vocabulaire',
                'gratuite'=> false,
                'ordre'   => 5,
                'contenu' => "# Les couleurs en allemand\n\nApprenez les couleurs pour décrire le monde qui vous entoure.",
                'mots'    => [
                    ['de' => 'rot',          'fr' => 'rouge',     'phonetique' => 'ʁoːt',          'exemple' => 'Das Auto ist rot.'],
                    ['de' => 'blau',         'fr' => 'bleu',      'phonetique' => 'blaʊ',          'exemple' => 'Der Himmel ist blau.'],
                    ['de' => 'grün',         'fr' => 'vert',      'phonetique' => 'ɡʁyːn',         'exemple' => 'Das Gras ist grün.'],
                    ['de' => 'gelb',         'fr' => 'jaune',     'phonetique' => 'ɡɛlp',          'exemple' => 'Die Sonne ist gelb.'],
                    ['de' => 'schwarz',      'fr' => 'noir',      'phonetique' => 'ʃvaʁts',        'exemple' => 'Die Katze ist schwarz.'],
                    ['de' => 'weiß',         'fr' => 'blanc',     'phonetique' => 'vaɪs',          'exemple' => 'Der Schnee ist weiß.'],
                    ['de' => 'grau',         'fr' => 'gris',      'phonetique' => 'ɡʁaʊ',          'exemple' => 'Das Haus ist grau.'],
                    ['de' => 'braun',        'fr' => 'marron',    'phonetique' => 'bʁaʊn',         'exemple' => 'Der Hund ist braun.'],
                    ['de' => 'rosa',         'fr' => 'rose',      'phonetique' => 'ˈʁoːza',        'exemple' => 'Das Kleid ist rosa.'],
                    ['de' => 'orange',       'fr' => 'orange',    'phonetique' => 'ɔˈʁɑ̃ːʒ',       'exemple' => 'Die Orange ist orange.'],
                    ['de' => 'lila',         'fr' => 'violet',    'phonetique' => 'ˈliːla',        'exemple' => 'Die Blume ist lila.'],
                ],
                'exercices' => [
                    [
                        'question'    => 'Quelle est la couleur du ciel en allemand ?',
                        'type'        => 'qcm',
                        'choix'       => ['rot', 'grün', 'blau', 'gelb'],
                        'reponse'     => 'blau',
                        'explication' => '"Der Himmel ist blau." = Le ciel est bleu.',
                    ],
                    [
                        'question'    => 'Traduisez "noir" en allemand',
                        'type'        => 'texte_libre',
                        'reponse'     => 'schwarz',
                        'explication' => '"schwarz" = noir. "weiß" = blanc, "grau" = gris.',
                    ],
                    [
                        'question'    => '"Das Gras ist ___." Quelle couleur complète ?',
                        'type'        => 'qcm',
                        'choix'       => ['rot', 'blau', 'grün', 'gelb'],
                        'reponse'     => 'grün',
                        'explication' => 'L\'herbe est verte. "Das Gras ist grün."',
                    ],
                ],
                'points_recompense' => 10,
            ],
        ];

        foreach ($lecons as $l) {
            Lesson::firstOrCreate(
                ['slug' => $l['slug']],
                array_merge($l, ['cours_id' => $cours->id])
            );
        }
    }

    // ════════════════ NIVEAU A2 ════════════════

    private function seedNiveauA2(): void
    {
        $cours = Course::firstOrCreate(['slug' => 'allemand-a2'], [
            'titre'        => 'Allemand A2 — Élémentaire',
            'sous_titre'   => 'Communiquer au quotidien',
            'description'  => 'Élargissez votre vocabulaire et vos compétences pour des situations du quotidien : courses, transport, restaurant.',
            'niveau'       => 'A2',
            'couleur'      => '#54a3f3',
            'icone'        => 'bi-2-circle',
            'duree_estimee_minutes' => 12,
            'gratuit'      => false,
            'publie'        => true,
            'ordre'        => 2,
        ]);

        $lecons = [
            [
                'titre'    => 'Au restaurant — Im Restaurant',
                'slug'     => 'a2-restaurant',
                'type'     => 'dialogue',
                'gratuite' => true,
                'ordre'    => 1,
                'contenu'  => "# Au restaurant en allemand\n\nApprenez à commander, demander la carte et régler l'addition.",
                'mots'     => [
                    ['de' => 'die Speisekarte',        'fr' => 'la carte / le menu',   'phonetique' => 'diː ˈʃpaɪzəˌkaʁtə', 'exemple' => 'Die Speisekarte, bitte!'],
                    ['de' => 'Ich möchte bestellen',   'fr' => 'Je voudrais commander', 'phonetique' => 'ɪç ˈmœçtə bəˈʃtɛlən','exemple' => 'Ich möchte bestellen, bitte.'],
                    ['de' => 'Was empfehlen Sie?',     'fr' => 'Que recommandez-vous ?','phonetique' => 'vas ɛmˈpfeːlən ziː', 'exemple' => 'Was empfehlen Sie heute?'],
                    ['de' => 'die Rechnung',           'fr' => 'l\'addition',           'phonetique' => 'diː ˈʁɛçnʊŋ',      'exemple' => 'Die Rechnung, bitte!'],
                    ['de' => 'getrennt oder zusammen?','fr' => 'séparé ou ensemble ?',  'phonetique' => 'ɡəˈtʁɛnt oːdɐ tsuˈzamən','exemple' => 'Getrennt oder zusammen?'],
                    ['de' => 'das Trinkgeld',          'fr' => 'le pourboire',          'phonetique' => 'das ˈtʁɪŋkˌɡɛlt',  'exemple' => 'Das Trinkgeld ist inbegriffen.'],
                    ['de' => 'Ich bin Vegetarier/in',  'fr' => 'Je suis végétarien(ne)','phonetique' => 'ɪç bɪn veɡeˈtaːʁɪɐ̯','exemple' => 'Ich bin Vegetarierin. Was können Sie empfehlen?'],
                    ['de' => 'Guten Appetit!',         'fr' => 'Bon appétit !',         'phonetique' => 'ˈɡuːtən aˈpetiːt', 'exemple' => 'Guten Appetit! Das sieht lecker aus.'],
                    ['de' => 'Es schmeckt sehr gut!',  'fr' => 'C\'est très bon !',     'phonetique' => 'ɛs ʃmɛkt zeːɐ̯ ɡuːt','exemple' => 'Es schmeckt sehr gut, danke!'],
                ],
                'exercices' => [
                    [
                        'question'    => 'Comment demander l\'addition ?',
                        'type'        => 'qcm',
                        'choix'       => ['Die Speisekarte, bitte', 'Die Rechnung, bitte', 'Guten Appetit', 'Es schmeckt gut'],
                        'reponse'     => 'Die Rechnung, bitte',
                        'explication' => '"Die Rechnung" = l\'addition. "bitte" = s\'il vous plaît.',
                    ],
                    [
                        'question'    => 'Que signifie "Es schmeckt sehr gut" ?',
                        'type'        => 'qcm',
                        'choix'       => ['J\'ai faim', 'C\'est très bon', 'L\'addition please', 'Le menu'],
                        'reponse'     => 'C\'est très bon',
                        'explication' => '"Schmecken" = avoir bon goût. "sehr" = très. "gut" = bien/bon.',
                    ],
                ],
                'points_recompense' => 15,
            ],
            [
                'titre'    => 'Les transports — Verkehrsmittel',
                'slug'     => 'a2-transports',
                'type'     => 'vocabulaire',
                'gratuite' => false,
                'ordre'    => 2,
                'contenu'  => "# Les transports en allemand\n\nVoyager en Allemagne nécessite de connaître le vocabulaire des transports.",
                'mots'     => [
                    ['de' => 'der Zug',           'fr' => 'le train',          'phonetique' => 'deːɐ tsuːk',       'exemple' => 'Der Zug fährt um 9 Uhr ab.'],
                    ['de' => 'das Flugzeug',      'fr' => 'l\'avion',          'phonetique' => 'das ˈfluːkˌtsɔɪ̯k', 'exemple' => 'Das Flugzeug landet in Frankfurt.'],
                    ['de' => 'die U-Bahn',        'fr' => 'le métro',          'phonetique' => 'diː ˈuːˌbaːn',     'exemple' => 'Die U-Bahn kommt in 3 Minuten.'],
                    ['de' => 'der Bus',           'fr' => 'le bus',            'phonetique' => 'deːɐ bʊs',         'exemple' => 'Welcher Bus fährt zum Bahnhof?'],
                    ['de' => 'das Taxi',          'fr' => 'le taxi',           'phonetique' => 'das ˈtaksi',       'exemple' => 'Ich brauche ein Taxi.'],
                    ['de' => 'das Fahrrad',       'fr' => 'le vélo',           'phonetique' => 'das ˈfaːɐ̯ˌʁaːt',  'exemple' => 'In Berlin fahre ich mit dem Fahrrad.'],
                    ['de' => 'der Bahnhof',       'fr' => 'la gare',           'phonetique' => 'deːɐ ˈbaːnˌhoːf', 'exemple' => 'Wo ist der Hauptbahnhof?'],
                    ['de' => 'die Haltestelle',   'fr' => 'l\'arrêt de bus',   'phonetique' => 'diː ˈhaltəˌʃtɛlə','exemple' => 'Ist das die Haltestelle?'],
                    ['de' => 'das Ticket',        'fr' => 'le ticket / billet','phonetique' => 'das ˈtɪkɪt',      'exemple' => 'Wo kaufe ich ein Ticket?'],
                    ['de' => 'Wo ist...?',        'fr' => 'Où est... ?',       'phonetique' => 'voː ɪst',          'exemple' => 'Entschuldigung, wo ist die U-Bahn?'],
                ],
                'exercices' => [
                    [
                        'question'    => 'Comment dit-on "le métro" ?',
                        'type'        => 'qcm',
                        'choix'       => ['der Zug', 'die U-Bahn', 'das Flugzeug', 'der Bus'],
                        'reponse'     => 'die U-Bahn',
                        'explication' => '"die U-Bahn" = le métro. U = Untergrundbahn (train souterrain).',
                    ],
                    [
                        'question'    => 'Demandez où se trouve la gare',
                        'type'        => 'texte_libre',
                        'reponse'     => 'Wo ist der Bahnhof',
                        'explication' => '"Wo ist der Bahnhof?" = Où est la gare ? Ajoutez "bitte" pour être poli.',
                    ],
                ],
                'points_recompense' => 15,
            ],
        ];

        foreach ($lecons as $l) {
            Lesson::firstOrCreate(
                ['slug' => $l['slug']],
                array_merge($l, ['cours_id' => $cours->id])
            );
        }
    }

    // ════════════════ NIVEAU B1 ════════════════

    private function seedNiveauB1(): void
    {
        $cours = Course::firstOrCreate(['slug' => 'allemand-b1'], [
            'titre'        => 'Allemand B1 — Intermédiaire',
            'sous_titre'   => 'Vers la fluidité conversationnelle',
            'description'  => 'Maîtrisez les temps, les cas grammaticaux et élargissez votre expression pour le travail et la vie en Allemagne.',
            'niveau'       => 'B1',
            'couleur'      => '#F5A623',
            'icone'        => 'bi-3-circle',
            'duree_estimee_minutes' => 20,
            'gratuit'      => false,
            'publie'        => true,
            'ordre'        => 3,
        ]);

        $lecons = [
            [
                'titre'    => 'Le Präteritum — Passé simple',
                'slug'     => 'b1-prateritum',
                'type'     => 'grammaire',
                'gratuite' => true,
                'ordre'    => 1,
                'contenu'  => "# Le Präteritum en allemand\n\nLe Präteritum est le temps du passé utilisé principalement à l'écrit et pour les verbes \"sein\" et \"haben\".",
                'mots'     => [
                    ['de' => 'ich war',      'fr' => 'j\'étais / je fus',    'phonetique' => 'ɪç vaːɐ̯', 'exemple' => 'Gestern war ich in Berlin.'],
                    ['de' => 'er/sie war',   'fr' => 'il/elle était',        'phonetique' => 'eːɐ vaːɐ̯', 'exemple' => 'Das Wetter war schön.'],
                    ['de' => 'ich hatte',    'fr' => 'j\'avais / j\'eus',    'phonetique' => 'ɪç ˈhatə',  'exemple' => 'Ich hatte keine Zeit.'],
                    ['de' => 'es gab',       'fr' => 'il y avait',           'phonetique' => 'ɛs ɡaːp',   'exemple' => 'Es gab ein Problem.'],
                    ['de' => 'ich ging',     'fr' => 'j\'allais / j\'allai', 'phonetique' => 'ɪç ɡɪŋ',   'exemple' => 'Ich ging gestern ins Kino.'],
                    ['de' => 'ich kam',      'fr' => 'je venais / je vins',  'phonetique' => 'ɪç kaːm',   'exemple' => 'Ich kam um 9 Uhr an.'],
                    ['de' => 'ich machte',   'fr' => 'je faisais / je fis',  'phonetique' => 'ɪç ˈmaxtə', 'exemple' => 'Ich machte meine Hausaufgaben.'],
                    ['de' => 'ich sprach',   'fr' => 'je parlais / je parlai','phonetique' => 'ɪç ʃpʁaːx', 'exemple' => 'Ich sprach mit meinem Chef.'],
                ],
                'exercices' => [
                    [
                        'question'    => 'Conjuguez "sein" au Präteritum, 1ère personne singulier',
                        'type'        => 'texte_libre',
                        'reponse'     => 'war',
                        'explication' => '"ich war" = j\'étais. Sein est irrégulier au Präteritum.',
                    ],
                    [
                        'question'    => '"Gestern ___ ich in Berlin." Complétez',
                        'type'        => 'qcm',
                        'choix'       => ['bin', 'war', 'habe', 'wurde'],
                        'reponse'     => 'war',
                        'explication' => 'Au Präteritum, "sein" devient "war". "Gestern" (hier) déclenche le passé.',
                    ],
                    [
                        'question'    => 'Traduisez : "Il y avait un problème"',
                        'type'        => 'texte_libre',
                        'reponse'     => 'Es gab ein Problem',
                        'explication' => '"Es gab" est le Präteritum de "es gibt" (il y a).',
                    ],
                ],
                'points_recompense' => 25,
            ],
        ];

        foreach ($lecons as $l) {
            Lesson::firstOrCreate(
                ['slug' => $l['slug']],
                array_merge($l, ['cours_id' => $cours->id])
            );
        }
    }
}
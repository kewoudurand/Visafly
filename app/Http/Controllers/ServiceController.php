<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
class ServiceController extends Controller
{
    /**
     * Données de tous les services VisaFly.
     * En production, ces données peuvent venir d'une table `services` en BDD.
     */
    public static function allServices(): array
    {
        return [
 
            // ─── 1. Visa & Immigration ────────────────────────────────
            'visa-immigration' => [
                'slug'             => 'visa-immigration',
                'titre'            => 'Visa & Immigration',
                'icon'             => 'bi-globe',
                'meta_description' => 'Obtenez votre visa avec VisaFly. Accompagnement complet pour tous types de visa : tourisme, études, travail, famille.',
                'description_longue' => 'VisaFly vous accompagne dans toutes vos démarches de visa, de la préparation du dossier jusqu\'à l\'obtention du précieux sésame. Notre équipe d\'experts connaît les exigences de chaque pays et vous guide à chaque étape pour maximiser vos chances de succès.',
                'delai'            => '3 à 16 semaines selon le pays',
                'contenu_html'     => '
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        L\'obtention d\'un visa peut sembler complexe, mais avec VisaFly à vos côtés, chaque étape devient claire et maîtrisée. Nous avons accompagné plus de 100 dossiers avec succès, pour des destinations en Europe, en Amérique du Nord et en Asie.
                    </p>
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        Notre approche : analyse de votre profil, identification du visa le plus adapté, constitution du dossier optimal, préparation à l\'entretien consulaire si nécessaire, et suivi jusqu\'à la réponse.
                    </p>
                    <p style="font-size:14px;color:#555;line-height:1.8;">
                        Nous traitons les visas pour la France, le Canada, l\'Allemagne, la Belgique, le Portugal, les États-Unis, et bien d\'autres destinations selon votre projet.
                    </p>
                ',
                'stats' => [
                    ['valeur' => '100+', 'label' => 'Visa obtenus'],
                    ['valeur' => '94%',  'label' => 'Taux de succès'],
                    ['valeur' => '6+',   'label' => 'Pays couverts'],
                    ['valeur' => '6j/7', 'label' => 'Disponibilité'],
                ],
                'etapes' => [
                    ['titre' => 'Analyse de votre profil',       'description' => 'Nous évaluons votre situation, votre objectif et identifions le type de visa le plus adapté.', 'badge' => '1–2 jours', 'badge_bg' => 'rgba(28,200,138,.1)', 'badge_color' => '#0f6e56', 'badge_icon' => 'bi-check-circle'],
                    ['titre' => 'Constitution du dossier',        'description' => 'Notre équipe vous fournit la liste exacte des documents requis et vérifie chaque pièce avant soumission.', 'badge' => '1 semaine', 'badge_bg' => 'rgba(27,58,107,.08)', 'badge_color' => '#1B3A6B', 'badge_icon' => 'bi-folder'],
                    ['titre' => 'Prise de rendez-vous consulaire','description' => 'Nous gérons la prise de rendez-vous auprès du consulat ou du centre VFS pour vous éviter les délais.', 'badge' => '1–4 semaines', 'badge_bg' => 'rgba(245,166,35,.1)', 'badge_color' => '#633806', 'badge_icon' => 'bi-calendar'],
                    ['titre' => 'Préparation à l\'entretien',     'description' => 'Pour les pays exigeant un entretien (USA, UK), nous simulons l\'entretien et préparons vos réponses.', 'badge' => '2–3 sessions', 'badge_bg' => 'rgba(83,74,183,.1)', 'badge_color' => '#534AB7', 'badge_icon' => 'bi-person-video'],
                    ['titre' => 'Suivi et réponse',               'description' => 'Nous suivons l\'avancement de votre dossier et vous alertons dès que le résultat est disponible.', 'badge' => 'Continu', 'badge_bg' => 'rgba(28,200,138,.1)', 'badge_color' => '#0f6e56', 'badge_icon' => 'bi-bell'],
                ],
                'avantages' => [
                    ['icon' => 'bi-shield-check',      'icon_class' => '',       'titre' => 'Dossiers vérifiés',           'texte' => 'Chaque document est contrôlé avant soumission pour éviter les rejets sur motif administratif.'],
                    ['icon' => 'bi-graph-up-arrow',    'icon_class' => 'gold',   'titre' => 'Taux de succès 94%',          'texte' => 'Notre expertise et notre maîtrise des exigences consulaires garantissent un taux d\'approbation très élevé.'],
                    ['icon' => 'bi-headset',           'icon_class' => '',       'titre' => 'Suivi personnalisé',          'texte' => 'Un conseiller dédié est attribué à votre dossier et reste joignable du lundi au samedi.'],
                    ['icon' => 'bi-lightning-charge',  'icon_class' => 'green',  'titre' => 'Délais optimisés',            'texte' => 'Grâce à notre réseau et notre expérience, nous réduisons les délais d\'attente au minimum possible.'],
                ],
                'documents' => [
                    'Passeport valide',
                    'Photos d\'identité conformes',
                    'Formulaire de demande rempli',
                    'Justificatifs financiers',
                    'Preuve d\'hébergement',
                    'Assurance voyage / santé',
                    'Lettre de motivation si requise',
                    'Casier judiciaire (selon pays)',
                ],
                'temoignage' => [
                    'texte'        => 'Grâce à VisaFly, j\'ai obtenu mon visa de travail pour l\'Allemagne en moins de 6 semaines. Le dossier était parfaitement préparé, l\'entretien s\'est très bien passé.',
                    'prenom'       => 'Rodrigue',
                    'nom_initial'  => 'T',
                    'destination'  => 'Berlin, Allemagne',
                ],
            ],
 
            // ─── 2. Études à l'étranger ───────────────────────────────
            'etudes-etranger' => [
                'slug'             => 'etudes-etranger',
                'titre'            => 'Études à l\'étranger',
                'icon'             => 'bi-mortarboard',
                'meta_description' => 'Partez étudier en France, au Canada, en Allemagne ou en Belgique avec l\'accompagnement complet de VisaFly International.',
                'description_longue' => 'VisaFly vous guide de l\'admission universitaire jusqu\'à votre installation dans votre pays d\'étude. Nous travaillons avec des partenariats universitaires en France, au Canada, en Allemagne et en Belgique pour maximiser vos chances d\'admission.',
                'delai'            => '2 à 4 mois',
                'contenu_html'     => '
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        Partir étudier à l\'étranger est un projet de vie qui nécessite une préparation rigoureuse. VisaFly vous accompagne sur tous les aspects : choix de l\'établissement, procédures d\'admission, visa étudiant, logement et intégration.
                    </p>
                    <p style="font-size:14px;color:#555;line-height:1.8;">
                        Nous avons aidé des dizaines d\'étudiants camerounais à intégrer des universités en France (Campus France), au Canada (EED), en Allemagne (DAAD) et en Belgique.
                    </p>
                ',
                'stats' => [
                    ['valeur' => '50+', 'label' => 'Étudiants placés'],
                    ['valeur' => '4',   'label' => 'Pays couverts'],
                    ['valeur' => '92%', 'label' => 'Taux admission'],
                    ['valeur' => '24h', 'label' => 'Réponse conseil'],
                ],
                'etapes' => [
                    ['titre' => 'Bilan académique et orientation', 'description' => 'Analyse de votre parcours, de votre niveau linguistique et de vos ambitions pour identifier les meilleures filières et établissements.'],
                    ['titre' => 'Sélection des universités',        'description' => 'Constitution d\'une liste d\'établissements correspondant à votre profil avec les chances d\'admission les plus élevées.'],
                    ['titre' => 'Préparation du dossier d\'admission', 'description' => 'Lettre de motivation, CV, lettres de recommandation, relevés de notes — tout est soigneusement préparé et traduit si nécessaire.'],
                    ['titre' => 'Demande de visa étudiant',         'description' => 'Une fois la lettre d\'admission obtenue, VisaFly prend en charge la totalité de la procédure de visa étudiant.'],
                    ['titre' => 'Accueil et installation',          'description' => 'Recherche de logement, ouverture de compte bancaire, CAF, couverture santé — VisaFly reste à vos côtés à l\'arrivée.'],
                ],
                'avantages' => [
                    ['icon' => 'bi-building',       'icon_class' => '',     'titre' => 'Réseau universitaire',   'texte' => 'Partenariats avec des établissements en France, Canada, Allemagne, Belgique.'],
                    ['icon' => 'bi-translate',      'icon_class' => 'gold', 'titre' => 'Préparation linguistique','texte' => 'Accès à nos cours TCF, TEF, IELTS et cours d\'allemand intégrés à la plateforme.'],
                    ['icon' => 'bi-house-heart',    'icon_class' => 'green','titre' => 'Aide au logement',       'texte' => 'Recherche de cité universitaire, colocation ou appartement selon votre budget.'],
                    ['icon' => 'bi-wallet2',        'icon_class' => '',     'titre' => 'Orientation bourses',    'texte' => 'Information sur les bourses disponibles (DAAD, Campus France, gouvernement canadien).'],
                ],
                'documents' => [
                    'Diplôme de Baccalauréat ou licence',
                    'Relevés de notes officiels',
                    'Passeport valide',
                    'Lettre d\'admission (à obtenir)',
                    'Justificatifs financiers',
                    'Lettre de motivation',
                    'Lettres de recommandation (x2)',
                    'Résultats TCF/IELTS si requis',
                ],
                'temoignage' => [
                    'texte'       => 'VisaFly m\'a accompagné depuis le choix de mon université en France jusqu\'à mon arrivée à Paris. Le dossier Campus France était impeccable et j\'ai été admise du premier coup.',
                    'prenom'      => 'Christelle',
                    'nom_initial' => 'N',
                    'destination' => 'Paris, France',
                ],
            ],
 
            // ─── 3. Emploi international ──────────────────────────────
            'emploi-international' => [
                'slug'             => 'emploi-international',
                'titre'            => 'Emploi international',
                'icon'             => 'bi-briefcase',
                'meta_description' => 'Trouvez un emploi en Europe avec VisaFly. Placement en CDI, accompagnement permis de travail, mise en relation employeurs.',
                'description_longue' => 'VisaFly vous met en relation avec des employeurs européens et vous accompagne dans toutes les démarches liées au permis de travail. De l\'optimisation de votre CV à la signature du contrat, nous sommes à chaque étape.',
                'delai'            => '6 à 20 semaines',
                'contenu_html'     => '
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        Le marché du travail européen offre de nombreuses opportunités pour les talents africains qualifiés. VisaFly dispose d\'un réseau de partenaires employeurs en Allemagne, en Belgique et en France, actifs dans les secteurs de l\'informatique, la santé, la construction et l\'hôtellerie.
                    </p>
                ',
                'stats' => [
                    ['valeur' => '30+', 'label' => 'Placement CDI'],
                    ['valeur' => '3',   'label' => 'Pays partenaires'],
                    ['valeur' => '85%', 'label' => 'Taux placement'],
                    ['valeur' => '48h', 'label' => 'Réponse offres'],
                ],
                'etapes' => [
                    ['titre' => 'Analyse de votre profil professionnel', 'description' => 'Évaluation de vos compétences, expériences, niveaux linguistiques et secteur d\'activité cible.'],
                    ['titre' => 'Optimisation du CV et profil LinkedIn', 'description' => 'Mise aux normes européennes de votre CV avec mise en avant des compétences clés selon le pays cible.'],
                    ['titre' => 'Mise en relation avec les employeurs',  'description' => 'Soumission de votre candidature à notre réseau d\'employeurs partenaires et suivi des réponses.'],
                    ['titre' => 'Obtention du contrat de travail',       'description' => 'Vérification juridique du contrat, assistance à la négociation, confirmation de l\'offre.'],
                    ['titre' => 'Visa de travail et installation',       'description' => 'Une fois le contrat signé, VisaFly prend en charge la totalité de la procédure de visa de travail.'],
                ],
                'avantages' => [
                    ['icon' => 'bi-people',        'icon_class' => '',     'titre' => 'Réseau employeurs',      'texte' => 'Plus de 50 entreprises partenaires en Allemagne, Belgique et France.'],
                    ['icon' => 'bi-file-person',   'icon_class' => 'gold', 'titre' => 'CV aux normes européennes','texte' => 'CV et lettre de motivation optimisés selon les standards des pays cibles.'],
                    ['icon' => 'bi-translate',     'icon_class' => 'green','titre' => 'Formation linguistique', 'texte' => 'Cours d\'allemand intégrés pour les candidats visant l\'Allemagne.'],
                    ['icon' => 'bi-award',         'icon_class' => '',     'titre' => 'Reconnaissance diplôme', 'texte' => 'Accompagnement pour la reconnaissance officielle de vos diplômes en Europe.'],
                ],
                'documents' => [
                    'CV en format européen (Europass)',
                    'Lettres de référence employeurs',
                    'Diplômes et attestations',
                    'Niveau linguistique certifié',
                    'Passeport valide',
                    'Casier judiciaire vierge',
                    'Autorisation de travail (EIMT/contrat)',
                    'Photos d\'identité',
                ],
                'temoignage' => [
                    'texte'       => 'J\'ai obtenu un CDI à Bruxelles grâce à VisaFly. Ils ont géré mon CV, mis en relation avec l\'employeur et obtenu mon permis de travail. Service exceptionnel !',
                    'prenom'      => 'Marc-André',
                    'nom_initial' => 'F',
                    'destination' => 'Bruxelles, Belgique',
                ],
            ],
 
            // ─── 4. Assurance voyage ──────────────────────────────────
            'assurance-voyage' => [
                'slug'             => 'assurance-voyage',
                'titre'            => 'Assurance voyage & santé',
                'icon'             => 'bi-heart-pulse',
                'meta_description' => 'Souscrivez à une assurance voyage adaptée à votre destination avec VisaFly. Couverture santé, rapatriement, annulation.',
                'description_longue' => 'Voyager sans assurance, c\'est prendre un risque inutile. VisaFly vous aide à souscrire à l\'assurance la plus adaptée à votre destination, votre profil et votre budget — en toute simplicité.',
                'delai'            => '24 à 48 heures',
                'contenu_html'     => '
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        Obligatoire pour les visas Schengen (minimum 30 000 €), recommandée pour toutes les destinations, l\'assurance voyage protège contre les frais médicaux imprévus, l\'hospitalisation, le rapatriement, et les annulations de voyage.
                    </p>
                ',
                'stats' => [
                    ['valeur' => '200+', 'label' => 'Clients assurés'],
                    ['valeur' => '24h',  'label' => 'Souscription'],
                    ['valeur' => '30K€', 'label' => 'Couverture min. Schengen'],
                    ['valeur' => '100%', 'label' => 'Conformité Schengen'],
                ],
                'etapes' => [
                    ['titre' => 'Identification de vos besoins',    'description' => 'Selon votre destination, durée du séjour et activités prévues, nous identifions la formule la plus adaptée.'],
                    ['titre' => 'Comparaison des offres',            'description' => 'VisaFly compare les offres de nos partenaires assureurs pour vous proposer le meilleur rapport qualité/prix.'],
                    ['titre' => 'Souscription rapide',              'description' => 'Processus de souscription simple et rapide — votre attestation d\'assurance est disponible sous 24h.'],
                    ['titre' => 'Attestation pour le consulat',     'description' => 'L\'attestation délivrée est conforme aux exigences consulaires et directement intégrable dans votre dossier visa.'],
                ],
                'avantages' => [
                    ['icon' => 'bi-shield-check',    'icon_class' => 'green', 'titre' => 'Conformité consulaire', 'texte' => 'Toutes nos assurances sont acceptées par les consulats Schengen et autres ambassades.'],
                    ['icon' => 'bi-cash-stack',      'icon_class' => '',      'titre' => 'Prix compétitifs',      'texte' => 'Nous négocions des tarifs préférentiels grâce à notre volume de souscriptions.'],
                    ['icon' => 'bi-telephone',       'icon_class' => 'gold',  'titre' => 'Assistance 24h/24',     'texte' => 'En cas d\'urgence à l\'étranger, une ligne d\'assistance est disponible nuit et jour.'],
                    ['icon' => 'bi-file-earmark-check','icon_class' => '',    'titre' => 'Attestation immédiate', 'texte' => 'Certificat d\'assurance disponible sous 24h, format consulaire accepté.'],
                ],
                'documents' => [],
                'temoignage' => null,
            ],
 
            // ─── 5. Billets d'avion ───────────────────────────────────
            'billets-avion' => [
                'slug'             => 'billets-avion',
                'titre'            => 'Achat de billets d\'avion',
                'icon'             => 'bi-airplane',
                'meta_description' => 'Réservez vos billets d\'avion au meilleur prix avec VisaFly. Vols depuis Douala et Yaoundé vers toutes les destinations.',
                'description_longue' => 'VisaFly vous aide à trouver et réserver les meilleurs vols depuis le Cameroun vers vos destinations internationales, au tarif le plus avantageux et selon vos contraintes de date.',
                'delai'            => '24 à 72 heures',
                'contenu_html'     => '
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        Trouver le bon vol au bon prix peut être complexe avec les multiples compagnies et escales disponibles. VisaFly compare les offres et vous propose le meilleur itinéraire selon votre budget et vos dates.
                    </p>
                ',
                'stats' => [
                    ['valeur' => '150+', 'label' => 'Réservations'],
                    ['valeur' => '-20%', 'label' => 'Économie moy.'],
                    ['valeur' => '48h',  'label' => 'Délai réservation'],
                    ['valeur' => '6j/7', 'label' => 'Disponibilité'],
                ],
                'etapes' => [
                    ['titre' => 'Définir votre itinéraire',        'description' => 'Date de départ, retour ou aller simple, escales acceptées, flexibilité — nous recueillons vos contraintes.'],
                    ['titre' => 'Recherche et comparaison',         'description' => 'Nous comparons les offres de toutes les compagnies desservant votre destination depuis YDE ou DLA.'],
                    ['titre' => 'Proposition et confirmation',      'description' => 'Vous recevez une sélection de 2 à 3 options avec prix, durées et compagnies. Vous confirmez votre choix.'],
                    ['titre' => 'Émission du billet',              'description' => 'Le billet électronique vous est transmis sous 24h. Il est directement utilisable pour le dossier visa.'],
                ],
                'avantages' => [
                    ['icon' => 'bi-currency-dollar', 'icon_class' => 'gold',  'titre' => 'Meilleurs tarifs',    'texte' => 'Accès aux tarifs négociés et aux promotions avant qu\'elles ne soient rendues publiques.'],
                    ['icon' => 'bi-clock-history',   'icon_class' => '',      'titre' => 'Gain de temps',       'texte' => 'Nous gérons toute la recherche et la réservation à votre place.'],
                    ['icon' => 'bi-file-earmark',    'icon_class' => 'green', 'titre' => 'Billet consulaire',   'texte' => 'Le billet fourni est accepté par les consulats comme preuve d\'intention de retour.'],
                    ['icon' => 'bi-telephone',       'icon_class' => '',      'titre' => 'Support 6j/7',        'texte' => 'En cas de modification ou d\'annulation, nous gérons les démarches avec la compagnie.'],
                ],
                'documents' => [],
                'temoignage' => null,
            ],
 
            // ─── 6. Hébergement ───────────────────────────────────────
            'hebergement' => [
                'slug'             => 'hebergement',
                'titre'            => 'Réservation d\'hébergement',
                'icon'             => 'bi-house-door',
                'meta_description' => 'VisaFly vous aide à trouver et réserver un logement dans votre pays de destination. Hôtel, résidence, colocation.',
                'description_longue' => 'La preuve d\'hébergement est souvent requise pour l\'obtention d\'un visa. VisaFly vous aide à trouver une solution adaptée à votre budget et à la durée de votre séjour, tout en répondant aux exigences consulaires.',
                'delai'            => '2 à 5 jours',
                'contenu_html'     => '
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        Que vous partiez pour un court séjour touristique ou pour vous installer durablement, VisaFly trouve la solution de logement la plus adaptée : hôtel, résidence universitaire, colocation, ou appartement meublé.
                    </p>
                ',
                'stats' => [
                    ['valeur' => '80+', 'label' => 'Logements réservés'],
                    ['valeur' => '5',   'label' => 'Pays couverts'],
                    ['valeur' => '48h', 'label' => 'Délai recherche'],
                    ['valeur' => '100%', 'label' => 'Conformité visa'],
                ],
                'etapes' => [
                    ['titre' => 'Définir vos besoins',    'description' => 'Budget, durée, ville, type de logement souhaité (chambre, studio, appartement, résidence universitaire).'],
                    ['titre' => 'Recherche et sélection', 'description' => 'VisaFly contacte ses partenaires locaux et plateformes pour vous proposer 2 à 3 options correspondant à vos critères.'],
                    ['titre' => 'Réservation et paiement','description' => 'Gestion complète de la réservation et du paiement sécurisé, avec délivrance de l\'attestation d\'hébergement.'],
                    ['titre' => 'Document consulaire',    'description' => 'L\'attestation fournie est au format consulaire, directement intégrable dans votre dossier visa.'],
                ],
                'avantages' => [
                    ['icon' => 'bi-house-check',  'icon_class' => 'green', 'titre' => 'Attestation consulaire',  'texte' => 'Document au format reconnu par tous les consulats pour votre dossier visa.'],
                    ['icon' => 'bi-geo-alt',      'icon_class' => '',      'titre' => 'Réseau local',            'texte' => 'Partenaires dans les principales villes : Paris, Montréal, Berlin, Bruxelles, Lisbonne.'],
                    ['icon' => 'bi-wallet2',      'icon_class' => 'gold',  'titre' => 'Tous budgets',           'texte' => 'Solutions disponibles de 200€/mois (colocation) à 2000€/mois (appartement meublé).'],
                    ['icon' => 'bi-shield',       'icon_class' => '',      'titre' => 'Logements vérifiés',      'texte' => 'Tous les hébergements proposés sont vérifiés et recommandés par notre réseau.'],
                ],
                'documents' => [],
                'temoignage' => null,
            ],
 
            // ─── 7. Import-Export ─────────────────────────────────────
            'import-export' => [
                'slug'             => 'import-export',
                'titre'            => 'Import — Export',
                'icon'             => 'bi-box-seam',
                'meta_description' => 'VisaFly accompagne vos opérations d\'import-export entre le Cameroun et l\'Europe. Sourcing, transport, formalités douanières.',
                'description_longue' => 'VisaFly offre un service d\'accompagnement pour les opérations commerciales internationales entre le Cameroun et l\'Europe : sourcing produits, logistique, transit douanier et livraison.',
                'delai'            => 'Variable selon opération',
                'contenu_html'     => '
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        Le commerce international nécessite une expertise en logistique, en réglementation douanière et en financement. VisaFly vous accompagne de la recherche du fournisseur jusqu\'à la livraison finale.
                    </p>
                ',
                'stats' => [
                    ['valeur' => '20+', 'label' => 'Opérations traitées'],
                    ['valeur' => '5',   'label' => 'Pays partenaires'],
                    ['valeur' => '48h', 'label' => 'Délai devis'],
                    ['valeur' => '6j/7','label' => 'Disponibilité'],
                ],
                'etapes' => [
                    ['titre' => 'Analyse de votre besoin commercial', 'description' => 'Identification du produit, volume, destination, budget et délais souhaités.'],
                    ['titre' => 'Sourcing et sélection fournisseur',  'description' => 'VisaFly identifie des fournisseurs fiables en Europe ou au Cameroun selon votre produit.'],
                    ['titre' => 'Gestion logistique et transport',    'description' => 'Organisation du transport (maritime, aérien ou routier), assurance marchandise, suivi en temps réel.'],
                    ['titre' => 'Formalités douanières',              'description' => 'Gestion complète des déclarations douanières, paiement des droits et taxes, dédouanement à l\'arrivée.'],
                ],
                'avantages' => [
                    ['icon' => 'bi-search',        'icon_class' => '',     'titre' => 'Sourcing fiable',       'texte' => 'Réseau de fournisseurs vérifiés en Europe, Asie et Afrique.'],
                    ['icon' => 'bi-truck',         'icon_class' => 'gold', 'titre' => 'Logistique complète',  'texte' => 'Transport, assurance, tracking — tout est géré par VisaFly.'],
                    ['icon' => 'bi-file-text',     'icon_class' => 'green','titre' => 'Formalités douanières','texte' => 'Gestion complète des documents d\'exportation et importation.'],
                    ['icon' => 'bi-currency-exchange','icon_class' => '',  'titre' => 'Conseil financement',  'texte' => 'Orientation vers les solutions de financement du commerce international.'],
                ],
                'documents' => [
                    'Facture proforma',
                    'Contrat commercial',
                    'Certificat d\'origine',
                    'Packing list',
                    'Connaissement ou LTA',
                    'Déclaration douanière',
                    'Assurance marchandise',
                    'Bordereau de livraison',
                ],
                'temoignage' => null,
            ],
 
            // ─── 8. Accompagnement installation ───────────────────────
            'accompagnement' => [
                'slug'             => 'accompagnement',
                'titre'            => 'Accompagnement jusqu\'à l\'installation',
                'icon'             => 'bi-person-walking',
                'meta_description' => 'VisaFly vous accompagne jusqu\'à votre installation à l\'étranger : logement, banque, sécurité sociale, intégration.',
                'description_longue' => 'Arriver dans un nouveau pays est une étape excitante mais souvent stressante. VisaFly reste à vos côtés bien au-delà de l\'obtention du visa pour assurer une installation fluide et sereine.',
                'delai'            => 'Dès votre arrivée',
                'contenu_html'     => '
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-bottom:14px;">
                        Notre service d\'accompagnement post-arrivée vous aide à naviguer dans les démarches administratives locales, à trouver un logement, à ouvrir un compte bancaire, à vous inscrire à la sécurité sociale et à vous intégrer dans votre nouvelle vie.
                    </p>
                ',
                'stats' => [
                    ['valeur' => '60+', 'label' => 'Clients accompagnés'],
                    ['valeur' => '5',   'label' => 'Pays d\'installation'],
                    ['valeur' => '97%', 'label' => 'Satisfaction clients'],
                    ['valeur' => '1 mois', 'label' => 'Durée accompagnement'],
                ],
                'etapes' => [
                    ['titre' => 'Accueil à l\'aéroport (optionnel)', 'description' => 'Nos partenaires locaux peuvent vous accueillir à l\'aéroport et vous conduire vers votre hébergement.'],
                    ['titre' => 'Ouverture de compte bancaire',       'description' => 'Accompagnement pour l\'ouverture d\'un compte dans une banque locale adaptée à votre situation.'],
                    ['titre' => 'Inscription sécurité sociale',       'description' => 'Démarches d\'inscription à la couverture santé locale (CPAM en France, RAMQ au Québec, etc.).'],
                    ['titre' => 'Démarches administratives locales',  'description' => 'Mairie, préfecture, titre de séjour, CAF — VisaFly vous guide dans chaque démarche.'],
                    ['titre' => 'Intégration et réseau',              'description' => 'Mise en contact avec la communauté camerounaise locale et les associations d\'expatriés.'],
                ],
                'avantages' => [
                    ['icon' => 'bi-globe',          'icon_class' => '',     'titre' => 'Réseau local',          'texte' => 'Contacts et partenaires dans les principales villes d\'accueil.'],
                    ['icon' => 'bi-translate',      'icon_class' => 'gold', 'titre' => 'Aide linguistique',    'texte' => 'Assistance pour les démarches en langue locale si nécessaire.'],
                    ['icon' => 'bi-phone',          'icon_class' => 'green','titre' => 'Suivi à distance',     'texte' => 'Votre conseiller VisaFly reste joignable pendant toute la période d\'installation.'],
                    ['icon' => 'bi-people',         'icon_class' => '',     'titre' => 'Réseau communautaire', 'texte' => 'Connexion avec la diaspora camerounaise dans votre ville d\'accueil.'],
                ],
                'documents' => [],
                'temoignage' => [
                    'texte'       => 'VisaFly était encore là après mon arrivée à Lisbonne. Ils m\'ont aidé à ouvrir mon compte à la Caixa Geral, à m\'inscrire au SNS (santé) et à trouver mon appartement. Incroyable !',
                    'prenom'      => 'Sandrine',
                    'nom_initial' => 'E',
                    'destination' => 'Lisbonne, Portugal',
                ],
            ],
 
        ]; // fin allServices()
    }
 
    /**
     * Affiche la page détail d'un service.
     */
    public function show(string $slug)
    {
        $services = $this->allServices();
 
        if (!array_key_exists($slug, $services)) {
            abort(404, 'Service introuvable.');
        }
 
        return view('service-details', [
            'service' => $services[$slug],
        ]);
    }
}
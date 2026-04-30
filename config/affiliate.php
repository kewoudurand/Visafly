<?php

// FILE: config/affiliate.php
// Créer ce fichier à la racine: config/affiliate.php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration Système d'Affiliation
    |--------------------------------------------------------------------------
    */

    /**
     * Activation/Désactivation du système
     */
    'enabled' => env('AFFILIATE_SYSTEM_ENABLED', true),

    /**
     * Préfixe du code de parrainage
     * Ex: VF_ABC123 pour VisaFly
     */
    'code_prefix' => env('AFFILIATE_CODE_PREFIX', 'VF'),

    /**
     * Longueur du code aléatoire
     */
    'code_length' => env('AFFILIATE_CODE_LENGTH', 10),

    /*
    |--------------------------------------------------------------------------
    | Configuration des Commissions
    |--------------------------------------------------------------------------
    */

    /**
     * Commission par défaut (montant fixe)
     */
    'default_commission' => env('AFFILIATE_DEFAULT_COMMISSION', 5000),

    /**
     * Commission en pourcentage (alternative)
     * Si null, on utilise la commission fixe
     */
    'commission_percentage' => env('AFFILIATE_COMMISSION_PERCENTAGE', null),

    /**
     * Montant minimum pour valider une affiliation
     */
    'minimum_purchase_amount' => env('AFFILIATE_MIN_PURCHASE', 0),

    /**
     * Commission bonus pour top affiliés
     * Format: [nombre_de_referrals => multiplicateur]
     */
    'bonus_tiers' => [
        10 => 1.5,   // 50% de bonus après 10 affiliés
        25 => 2.0,   // 100% de bonus après 25 affiliés
        50 => 2.5,   // 150% de bonus après 50 affiliés
        100 => 3.0,  // 200% de bonus après 100 affiliés
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des Parrainages Multi-niveaux
    |--------------------------------------------------------------------------
    */

    /**
     * Activer les commissions multi-niveaux
     */
    'multi_level_enabled' => env('AFFILIATE_MULTI_LEVEL', false),

    /**
     * Niveaux de commission
     * Format: [level => commission_amount or percentage]
     */
    'multi_level_commissions' => [
        1 => 5000,    // Niveau 1: commission complète
        2 => 1000,    // Niveau 2: 1000 au parrain du parrain
        // 3 => 500,   // Décommentez pour 3 niveaux
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration du Retrait
    |--------------------------------------------------------------------------
    */

    /**
     * Montant minimum pour retirer
     */
    'minimum_withdrawal' => env('AFFILIATE_MIN_WITHDRAWAL', 1000),

    /**
     * Montant maximum par retrait
     */
    'maximum_withdrawal' => env('AFFILIATE_MAX_WITHDRAWAL', 50000),

    /**
     * Frais de retrait (en pourcentage)
     */
    'withdrawal_fee_percentage' => env('AFFILIATE_WITHDRAWAL_FEE', 0),

    /**
     * Jours avant validation du retrait
     */
    'withdrawal_holding_period_days' => env('AFFILIATE_HOLDING_DAYS', 7),

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    /**
     * Notifier l'affilié quand une commission est complétée
     */
    'notify_on_completion' => env('AFFILIATE_NOTIFY_COMPLETION', true),

    /**
     * Notifier l'affilié quand un retrait est approuvé
     */
    'notify_on_withdrawal' => env('AFFILIATE_NOTIFY_WITHDRAWAL', true),

    /**
     * Email pour les rapports d'affiliation (admin)
     */
    'admin_email' => env('AFFILIATE_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS')),

    /*
    |--------------------------------------------------------------------------
    | Restrictions
    |--------------------------------------------------------------------------
    */

    /**
     * Permettre l'auto-parrainage (impossible par défaut)
     */
    'allow_self_referral' => env('AFFILIATE_ALLOW_SELF', false),

    /**
     * Jours avant qu'un affilié puisse être retiré du programme
     */
    'affiliate_lock_in_days' => env('AFFILIATE_LOCK_IN_DAYS', 0),

    /**
     * Rôles autorisés à être affiliés
     */
    'allowed_roles' => env('AFFILIATE_ALLOWED_ROLES', [
        // 'user',
        // 'premium_user',
    ]),

    /*
    |--------------------------------------------------------------------------
    | Lien de Partage
    |--------------------------------------------------------------------------
    */

    /**
     * Paramètre URL pour le code de parrainage
     */
    'referral_param' => env('AFFILIATE_REFERRAL_PARAM', 'ref'),

    /**
     * Routes où le code est utilisé
     */
    'referral_routes' => [
        'register',    // /register?ref=CODE
        'signup',      // Alternative
    ],

    /*
    |--------------------------------------------------------------------------
    | Rapports & Analytics
    |--------------------------------------------------------------------------
    */

    /**
     * Générer automatiquement les rapports quotidiens
     */
    'generate_daily_reports' => env('AFFILIATE_DAILY_REPORTS', false),

    /**
     * Sauvegarder l'historique des retraits
     */
    'track_withdrawal_history' => env('AFFILIATE_TRACK_WITHDRAWALS', true),

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    */

    /**
     * Mode debug (logs détaillés)
     */
    'debug' => env('AFFILIATE_DEBUG', env('APP_DEBUG', false)),

];
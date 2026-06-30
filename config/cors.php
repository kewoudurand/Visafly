<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */

    // Autorise toutes les routes commençant par /api
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Autorise toutes les méthodes HTTP (GET, POST, PUT, DELETE, OPTIONS, etc.)
    'allowed_methods' => ['*'],

    // TRÈS IMPORTANT : Autorise toutes les origines (indispensable pour les émulateurs et téléphones)
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    // Autorise tous les en-têtes HTTP (comme 'Authorization' contenant le Bearer Token)
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
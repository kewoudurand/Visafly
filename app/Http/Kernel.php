<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;
class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     *
     * Ces middleware sont exécutés pour **chaque requête**.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Protection contre les entrées malformées
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * Middleware de groupe pour "web".
     *
     * @var array<int, class-string|string>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Middleware disponibles pour les routes individuelles.
     *
     * Ces middleware peuvent être utilisés via ->middleware('nom')
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // ───────────── Spatie Permission Middleware ─────────────
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Chaque jour à 2h du matin: valider les parrainages
        $schedule->command('affiliate:complete-referrals --status=pending')
                ->dailyAt('02:00')
                ->name('affiliate.complete')
                ->withoutOverlapping()
                ->onSuccess(function () {
                    Log::info('✅ Parrainages validés');
                })
                ->onFailure(function () {
                    Log::error('❌ Erreur parrainages');
                    // Envoyer notification à l'admin
                });

        // Chaque jour à 3h du matin: mettre à jour les soldes
        $schedule->command('affiliate:update-balances')
                ->dailyAt('03:00')
                ->name('affiliate.balances')
                ->withoutOverlapping()
                ->onSuccess(function () {
                    Log::info('✅ Soldes mis à jour');
                });
    }
}
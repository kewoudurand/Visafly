<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\Tcf\TcfController;
use App\Http\Controllers\Tcf\TcfEpreuveController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\AbonnementController;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', fn() => view('index'))->name('home');

// Authentification
Route::get('/register', [RegisterController::class, 'show'])->name('auth.register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('auth.register.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Consultation publique
Route::get('/consultations', [ConsultationController::class, 'create'])->name('consultations.create');
Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');


/*
|--------------------------------------------------------------------------
| Routes protégées par auth
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard utilisateur
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/espace', [DashboardController::class, 'dashboard'])->name('dashboard.espace');

    /*
    |--------------------------------------------------------------------------
    | Routes TCF
    |--------------------------------------------------------------------------
    */
    Route::prefix('tcf')->name('tcf.')->middleware(['auth'])->group(function () {

        // Pages publiques pour utilisateurs connectés
        Route::get('/', [TcfController::class, 'index'])->name('index');
        Route::get('/abonnement', [TcfController::class, 'abonnement'])->name('abonnement');
        Route::get('/{serie:code}', [TcfController::class, 'disciplines'])->name('disciplines');

        // Démarrer une épreuve (nécessite permission)
        Route::post('/{serie:code}/{discipline:code}/demarrer', [TcfController::class, 'demarrer'])
            ->middleware('permission:pass test')
            ->name('demarrer');

        // Gestion épreuve TCF (nécessite permission)
        Route::middleware('permission:pass test')->group(function () {
            Route::get('/examen/{serie:code}/{discipline:code}/{question?}', [TcfEpreuveController::class, 'show'])->name('epreuve.show');
            Route::post('/examen/{serie:code}/{discipline:code}/repondre', [TcfEpreuveController::class, 'repondre'])
                ->name('epreuve.repondre');
            Route::get('/examen/{serie:code}/{discipline:code}/terminer', [TcfEpreuveController::class, 'terminer'])
                ->name('epreuve.terminer');
            Route::get('/examen/{serie:code}/{discipline:code}/resultats', [TcfEpreuveController::class, 'resultat'])
                ->name('epreuve.resultat');
        });
    });

    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

        // ── Utilisateurs ──
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/change-role',
            [UserController::class, 'changeRole'])->name('users.change-role');
        Route::post('/users/{user}/abonnement',
            [UserController::class, 'toggleAbonnement'])->name('users.toggle-abonnement');

        // ── Rôles ──
        Route::get('/roles',           [RolePermissionController::class, 'rolesIndex'])  ->name('roles.index');
        Route::post('/roles',          [RolePermissionController::class, 'rolesStore'])  ->name('roles.store');
        Route::put('/roles/{role}',    [RolePermissionController::class, 'rolesUpdate']) ->name('roles.update');
        Route::delete('/roles/{role}', [RolePermissionController::class, 'rolesDestroy'])->name('roles.destroy');

        // ── Permissions ──
        Route::get('/permissions',              [RolePermissionController::class, 'permissionsIndex'])  ->name('permissions.index');
        Route::post('/permissions',             [RolePermissionController::class, 'permissionsStore'])  ->name('permissions.store');
        Route::delete('/permissions/{permission}', [RolePermissionController::class, 'permissionsDestroy'])->name('permissions.destroy');

        // ── Abonnements ──
        Route::get('/abonnements', [AbonnementController::class, 'index'])->name('abonnements.index');
    });
});
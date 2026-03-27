<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserConsultationController;
use App\Http\Controllers\Admin\AdminConsultationController;
use App\Http\Controllers\Tcf\TcfController;
use App\Http\Controllers\Tcf\TcfEpreuveController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\AbonnementController;
use App\Http\Controllers\Admin\ConsultationAdminController;
use App\Http\Controllers\ProfileController;

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
Route::get('/consultations', [UserConsultationController::class, 'create'])->name('consultations.create');
Route::post('/consultations', [UserConsultationController::class, 'store'])->name('consultations.store');


// ─── CÔTÉ USER (public + connecté) ───────────────────────
// Route::get('/consultation',       [UserConsultationController::class, 'create'])->name('consultation');
// Route::post('/consultation',      [UserConsultationController::class, 'store'])->name('consultation.store');
Route::get('/consultation/merci', [UserConsultationController::class, 'merci'])->name('consultation.merci');
 
 
// ─── CÔTÉ ADMIN ──────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
 
    Route::get('/consultations',
        [AdminConsultationController::class, 'index'])->name('consultations.index');
 
    Route::get('/consultations/export',
        [AdminConsultationController::class, 'export'])->name('consultations.export');
 
    Route::get('/consultations/{consultation}',
        [AdminConsultationController::class, 'show'])->name('consultations.show');
 
    Route::post('/consultations/{consultation}/en-cours',
        [AdminConsultationController::class, 'enCours'])->name('consultations.en-cours');
 
    Route::post('/consultations/{consultation}/approuver',
        [AdminConsultationController::class, 'approuver'])->name('consultations.approuver');
 
    Route::post('/consultations/{consultation}/decliner',
        [AdminConsultationController::class, 'decliner'])->name('consultations.decliner');
 
    Route::post('/consultations/{consultation}/terminer',
        [AdminConsultationController::class, 'terminer'])->name('consultations.terminer');
 
    Route::post('/consultations/{consultation}/assigner',
        [AdminConsultationController::class, 'assigner'])->name('consultations.assigner');
 
    Route::post('/consultations/{consultation}/note',
        [AdminConsultationController::class, 'note'])->name('consultations.note');
 
    Route::post('/consultations/{consultation}/lien-visio',
        [AdminConsultationController::class, 'lienVisio'])->name('consultations.lien-visio');
 
    Route::post('/consultations/{consultation}/toggle-urgent',
        [AdminConsultationController::class, 'toggleUrgent'])->name('consultations.toggle-urgent');
 
    Route::delete('/consultations/{consultation}',
        [AdminConsultationController    ::class, 'destroy'])->name('consultations.destroy');
});


/*
|--------------------------------------------------------------------------
| Routes protégées par auth
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    Route::get('/profil',             [ProfileController::class, 'edit'])          ->name('profil.edit');
    Route::post('/profil',            [ProfileController::class, 'update'])        ->name('profil.update');
    Route::post('/profil/password',   [ProfileController::class, 'updatePassword'])->name('profil.password');
    Route::post('/profil/avatar',     [ProfileController::class, 'updateAvatar'])  ->name('profil.avatar');
    Route::delete('/profil/avatar',   [ProfileController::class, 'deleteAvatar'])  ->name('profil.avatar.delete');
    Route::delete('/profil',          [ProfileController::class, 'destroy'])       ->name('profil.delete');

    // Dashboard utilisateur
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/espace', [DashboardController::class, 'dashboard'])->name('dashboard.espace');
    Route::get('/espace', [UserConsultationController::class, 'index'])->name('dashboard');

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

    // Route::get('/consultations',[ConsultationController::class, 'index'])->name('consultations.index');
    // Route::get('/consultations/{consultation}',
    //     [ConsultationController::class, 'show'])->name('consultations.show');
 
    // Route::post('/consultations/{consultation}/approuver',
    //     [ConsultationController::class, 'approuver'])->name('consultations.approuver');
 
    // Route::post('/consultations/{consultation}/decliner',
    //     [ConsultationController::class, 'decliner'])->name('consultations.decliner');
 
    // Route::post('/consultations/{consultation}/en-cours',
    //     [ConsultationController::class, 'enCours'])->name('consultations.en-cours');
 
    // Route::post('/consultations/{consultation}/terminer',
    //     [ConsultationController::class, 'terminer'])->name('consultations.terminer');
 
    // Route::post('/consultations/{consultation}/assigner',
    //     [ConsultationController::class, 'assigner'])->name('consultations.assigner');
 
    // Route::post('/consultations/{consultation}/note',
    //     [ConsultationController::class, 'note'])->name('consultations.note');
 
    // Route::post('/consultations/{consultation}/lien-visio',
    //     [ConsultationController::class, 'lienVisio'])->name('consultations.lien-visio');
 
    // Route::post('/consultations/{consultation}/toggle-urgent',
    //     [ConsultationController::class, 'toggleUrgent'])->name('consultations.toggle-urgent');
 
    // Route::delete('/consultations/{consultation}',
    //     [ConsultationController::class, 'destroy'])->name('consultations.destroy');
 
    // Route::get('/consultations/export',
    //     [ConsultationController::class, 'export'])->name('consultations.export');

        // ── Utilisateurs ──
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/change-role',[UserController::class, 'changeRole'])->name('users.change-role');
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
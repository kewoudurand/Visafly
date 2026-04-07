<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserConsultationController;
use App\Http\Controllers\Admin\AdminConsultationController;
use App\Http\Controllers\Tcf\TcfController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AnalyticsLangueController;
use App\Http\Controllers\LangueEpreuveController;
use App\Http\Controllers\StudentResultController;
use App\Http\Controllers\Admin\LangueController;
use App\Http\Controllers\Admin\AbonnementPlanController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\NiveauController;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', fn() => view('index'))->name('home');

// Authentification
Route::get('/register', [RegisterController::class, 'show'])->name('auth.register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Consultation publique
Route::get('/consultations', [UserConsultationController::class, 'create'])->name('consultations.create');
Route::post('/consultations', [UserConsultationController::class, 'store'])->name('consultations.store');
Route::get('/consultation/merci', [UserConsultationController::class, 'merci'])->name('consultation.merci');
Route::get('/abonnement', [TcfController::class, 'abonnement'])->name('tcf.abonnement');
Route::get('/langues',[LangueEpreuveController::class, 'index'])->name('langues.index');
Route::get('/langues/{code}/series',[LangueEpreuveController::class, 'series'])->name('langues.series');


Route::middleware('auth')->group(function () {

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::post('/profil',[ProfileController::class, 'update'])->name('profil.update');
    Route::post('/profil/password',[ProfileController::class, 'updatePassword'])->name('profil.password');
    Route::post('/profil/avatar',[ProfileController::class, 'updateAvatar'])  ->name('profil.avatar');
    Route::delete('/profil/avatar',[ProfileController::class, 'deleteAvatar'])  ->name('profil.avatar.delete');
    Route::delete('/profil',[ProfileController::class, 'destroy'])->name('profil.delete');

    // Dashboard utilisateur
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/espace', [DashboardController::class, 'dashboard'])->name('dashboard.espace');
    Route::get('/espace', [UserConsultationController::class, 'index'])->name('dashboard');

    Route::get('/langues/{code}/series/{serie}/disciplines',[LangueEpreuveController::class, 'disciplines'])->name('langues.disciplines');
    Route::get('/langues/{code}/series/{serie}/disciplines/{discipline}/epreuve',[LangueEpreuveController::class, 'epreuve'])->name('langues.epreuve');
    Route::post('/langues/{code}/series/{serie}/disciplines/{discipline}/soumettre',[LangueEpreuveController::class, 'soumettre'])->name('langues.epreuve.soumettre');

    Route::get('/mon-abonnement',[AbonnementController::class, 'index'])->name('abonnement.index');
    Route::post('/abonnement/{plan}/souscrire', [AbonnementController::class, 'souscrire'])->name('abonnement.souscrire');



    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

        Route::get('/analytics/langues', [AnalyticsLangueController::class, 'index'])->name('analytics.langues');

        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

        // ── Utilisateurs ──
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/change-role',[UserController::class, 'changeRole'])->name('users.change-role');
        Route::post('/users/{user}/abonnement',[UserController::class, 'toggleAbonnement'])->name('users.toggle-abonnement');

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
        Route::post('/abonnements', [AbonnementController::class, 'store'])->name('abonnements.store');

        // ── Langues, séries, questions ──

        Route::get('/langues', [LangueController::class, 'index'])->name('langues.index');
        Route::get('/langues/{langue}',[LangueController::class, 'show'])->name('langues.show');
    
        Route::get('/langues/disciplines/{discipline}/series/create',[LangueController::class, 'createSerie'])->name('series.create');
        Route::post('/langues/disciplines/{discipline}/series',[LangueController::class, 'storeSerie'])->name('series.store');
        Route::get('/langues/series/{serie}',[LangueController::class, 'showSerie'])->name('series.show');
        Route::get('/langues/series/{serie}/edit',[LangueController::class, 'editSerie'])->name('series.edit');
        Route::put('/langues/series/{serie}',[LangueController::class, 'updateSerie'])->name('series.update');
        Route::delete('/langues/series/{serie}',[LangueController::class, 'destroySerie'])->name('series.destroy');
    
        Route::get('/langues/series/{serie}/questions/create',[LangueController::class, 'createQuestion']) ->name('questions.create');
        Route::post('/langues/series/{serie}/questions',[LangueController::class, 'storeQuestion'])  ->name('questions.store');
        Route::get('/langues/questions/{question}/edit',[LangueController::class, 'editQuestion'])   ->name('questions.edit');
        Route::put('/langues/questions/{question}',[LangueController::class, 'updateQuestion']) ->name('questions.update');
        Route::delete('/langues/questions/{question}',[LangueController::class, 'destroyQuestion'])->name('questions.destroy');

        // -consultations -
        Route::get('/consultations',[AdminConsultationController::class, 'index'])->name('consultations.index');
        Route::get('/consultations/export',[AdminConsultationController::class, 'export'])->name('consultations.export');
        Route::get('/consultations/{consultation}',[AdminConsultationController::class, 'show'])->name('consultations.show');
        Route::post('/consultations/{consultation}/en-cours',[AdminConsultationController::class, 'enCours'])->name('consultations.en-cours');
        Route::post('/consultations/{consultation}/approuver',[AdminConsultationController::class, 'approuver'])->name('consultations.approuver');
        Route::post('/consultations/{consultation}/decliner',[AdminConsultationController::class, 'decliner'])->name('consultations.decliner');
        Route::post('/consultations/{consultation}/terminer',[AdminConsultationController::class, 'terminer'])->name('consultations.terminer');
        Route::post('/consultations/{consultation}/assigner',[AdminConsultationController::class, 'assigner'])->name('consultations.assigner');
        Route::post('/consultations/{consultation}/note',[AdminConsultationController::class, 'note'])->name('consultations.note');
        Route::post('/consultations/{consultation}/lien-visio',[AdminConsultationController::class, 'lienVisio'])->name('consultations.lien-visio');
        Route::post('/consultations/{consultation}/toggle-urgent',[AdminConsultationController::class, 'toggleUrgent'])->name('consultations.toggle-urgent');
        Route::delete('/consultations/{consultation}',[AdminConsultationController::class, 'destroy'])->name('consultations.destroy');

        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/langues', [AnalyticsController::class, 'langues'])->name('analytics.langues');
 
        // ← NOUVELLE : détail analytique d'un utilisateur
        Route::get('/analytics/users/{user}', [AnalyticsController::class, 'userDetail'])->name('analytics.user');
    
        // Analytics spécifique langues (déjà existante)
        //Route::get('/analytics/langues', [AnalyticsLangueController::class, 'index'])->name('analytics.langues');
    
        // Plans abonnement
        Route::get(    '/abonnements/plans',               [AbonnementPlanController::class, 'index'])  ->name('abonnements.plans.index');
        Route::get(    '/abonnements/plans/create',        [AbonnementPlanController::class, 'create']) ->name('abonnements.plans.create');
        Route::post(   '/abonnements/plans',               [AbonnementPlanController::class, 'store'])  ->name('abonnements.plans.store');
        Route::get(    '/abonnements/plans/{plan}/edit',   [AbonnementPlanController::class, 'edit'])   ->name('abonnements.plans.edit');
        Route::put(    '/abonnements/plans/{plan}',        [AbonnementPlanController::class, 'update']) ->name('abonnements.plans.update');
        Route::delete( '/abonnements/plans/{plan}',        [AbonnementPlanController::class, 'destroy'])->name('abonnements.plans.destroy');
        Route::post(   '/abonnements/plans/{plan}/toggle', [AbonnementPlanController::class, 'toggle']) ->name('abonnements.plans.toggle');
    });

    Route::prefix('results')->name('student.')->group(function () {
        
        // Liste de tous les examens passés
        Route::get('/', [StudentResultController::class, 'index'])->name('index')->middleware('can:view result');
        
        // Résultats pour un examen spécifique (TCF, TEF, etc.)
        Route::get('/exam/{langue}', [StudentResultController::class, 'showExam'])->name('show')->middleware('can:view result');
        
        // Détails complets d'un passage
        Route::get('/passage/{passage}', [StudentResultController::class, 'showPassage'])->name('detail')->middleware('can:view result');

        Route::get('/langues',[LangueEpreuveController::class, 'index'])->name('langues.index');
    });

     
Route::prefix('langues')->name('langues.')->group(function () {
    
    // ... autres routes langues ...
    
    // ═══════════════════════════════════════════════════
    // ROUTES POUR LES NIVEAUX ET COURS
    // ═══════════════════════════════════════════════════
    
    // Sélection du niveau
    Route::get('/{langue}/niveaux', [NiveauController::class, 'selectNiveau'])
        ->name('niveaux');
    
    // Options (cours ou examen) pour un niveau
    Route::get('/{langue}/niveau/{niveau}/options', [NiveauController::class, 'selectOption'])
        ->name('options');
    
    // Liste des cours pour un niveau
    Route::get('/{langue}/niveau/{niveau}/cours', [NiveauController::class, 'afficherCours'])
        ->name('cours');
    
    // Détail d'un cours
    Route::get('/{langue}/cours/{cours}', [NiveauController::class, 'detailsCours'])
        ->name('cours-detail');
    
    // S'inscrire à un cours
    Route::post('/{langue}/cours/{cours}/inscrire', [NiveauController::class, 'inscrireCours'])
        ->middleware('auth')
        ->name('inscrire-cours');
    
    // Aller passer un examen
    Route::get('/{langue}/niveau/{niveau}/examen', [NiveauController::class, 'allerExamen'])
        ->name('examen');
    
});
 
});
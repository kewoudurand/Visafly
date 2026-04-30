<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserConsultationController;
use App\Http\Controllers\Admin\AdminConsultationController;
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
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Student\CourseDashboardController;
use App\Http\Controllers\Admin\StudentProgressController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Instructor\CourseInstructorController;
use App\Http\Controllers\Admin\LessonAdminController;
use App\Http\Controllers\Admin\AffiliateAdminController;
use App\Http\Controllers\Instructor\LessonInstructorController;
use App\Http\Controllers\ProgressionController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\WithdrawalController;


/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', function() {
    $controller = new \App\Http\Controllers\ServiceController();
    return view('index', [
        'services' => $controller->allServices() 
    ]);
})->name('home');


Route::get('/auth/google', [GoogleController::class,'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class,'callback']);

// Authentification
Route::get('/register', [RegisterController::class, 'show'])->name('auth.register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/blog/comment-obtenir-son-visa', function () {return view('blog.comment-obtenir-son-visa');})->name('blog.visa-guide');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show')->where('slug', '[a-z\-]+');

// Consultation publique
Route::get('/consultations', [UserConsultationController::class, 'create'])->name('consultations.create');
Route::post('/consultations', [UserConsultationController::class, 'store'])->name('consultations.store');
Route::get('/consultation/merci', [UserConsultationController::class, 'merci'])->name('consultation.merci');
Route::get('/langues',[LangueEpreuveController::class, 'index'])->name('langues.index');
Route::get('/langues/{code}/series',[LangueEpreuveController::class, 'series'])->name('langues.series');

Route::prefix('apprendre')->name('student.')->middleware(['auth', 'role:student|admin|super-admin'])->group(function () {
 
    Route::get('cours/{cours}',[CourseController::class, 'show'])->name('cours.show');
 
    Route::get('cours/{cours}/lecons/{lesson}',
        [LessonController::class, 'show']
    )->name('cours.lessons.show');
 
    Route::post('cours/{cours}/lecons/{lesson}/soumettre',
        [LessonController::class, 'soumettre']
    )->name('cours.lessons.soumettre');
 
    Route::post('cours/{cours}/lecons/{lesson}/terminer',
        [LessonController::class, 'terminer']
    )->name('cours.lessons.terminer');
});

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

    Route::get('/langues/{code}/series/{serie}/disciplines',[LangueEpreuveController::class, 'disciplines'])->name('langues.disciplines');
    Route::get('/langues/{code}/series/{serie}/disciplines/{discipline}/epreuve',[LangueEpreuveController::class, 'epreuve'])->name('langues.epreuve');
    Route::post('/langues/{code}/series/{serie}/disciplines/{discipline}/soumettre',[LangueEpreuveController::class, 'soumettre'])->name('langues.epreuve.soumettre');

    Route::get('/mon-abonnement',[AbonnementController::class, 'index'])->name('abonnement.index');
    Route::post('/abonnement/{plan}/souscrire', [AbonnementController::class, 'souscrire'])->name('abonnement.souscrire');
    Route::get('/choose',[CourseController::class, 'choose'])->name('chooses');

    Route::get('/mes-affiliation', [AffiliateController::class, 'dashboard'])->name('affiliate.dashboard');
    Route::get('/list', [AffiliateController::class, 'listAffiliates'])->name('affiliate.list');
    Route::get('/stats', [AffiliateController::class, 'stats'])->name('affiliate.stats');
    Route::get('/link', [AffiliateController::class, 'getAffiliateLink'])->name('affiliate.link');
    Route::post('/withdraw', [AffiliateController::class, 'withdraw'])->name('affiliate.withdraw');
    Route::get('/history', [AffiliateController::class, 'transactionHistory'])->name('affiliate.history');
    Route::prefix('cours')->name('cours.')->group(function () {
        Route::get('/',                  [CourseController::class, 'choose'])->name('list');
        Route::get('{cours:slug}',       [CourseController::class, 'show'])->name('allemand.show');
        Route::get('{cours:slug}/{lecon:slug}', [CourseController::class, 'lecon'])->middleware('auth')->name('allemand.lecon');
        Route::post('valider/{lecon}',   [CourseController::class, 'valider'])->middleware('auth')->name('allemand.valider');
    });

    Route::prefix('affiliate/withdraw')->name('affiliate.withdraw.')->group(function () {
    
        // ÉTAPE 1 : Formulaire du montant
        Route::get('/', [WithdrawalController::class, 'showForm'])->name('show-form');
        Route::post('/validate-amount', [WithdrawalController::class, 'validateAmount'])->name('validate-amount');

        // ÉTAPE 2 : Choix du moyen de paiement
        Route::get('/choose-method', [WithdrawalController::class, 'chooseMethod'])->name('show-method');

        // ÉTAPE 3 : Détails selon le moyen (Numéro OM, MTN, etc.)
        Route::get('/method/{method}', [WithdrawalController::class, 'showMethodDetails'])->name('method-details');

        // ÉTAPE 4 : Soumission finale
        Route::post('/submit', [WithdrawalController::class, 'submitWithdrawal'])->name('submit');

        // HISTORIQUE & ACTIONS
        Route::get('/history', [WithdrawalController::class, 'history'])->name('history');
        Route::post('/{withdrawal}/cancel', [WithdrawalController::class, 'cancelWithdrawal'])->name('cancel');
    });


    //Administrateur
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
        Route::post('/abonnements', [AbonnementPlanController::class, 'store'])->name('abonnements.store');

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

        Route::get('/student-progress',[StudentProgressController::class, 'index'])->name('student-progress.index');
        Route::get('/student-progress/{user}',[StudentProgressController::class, 'show'])->name('student-progress.show');

        Route::resource('cours',CourseController::class);
 
        // Leçons (nested sous cours)
        Route::resource('cours.lessons',LessonAdminController::class);
    
        // Réordonner leçons (drag & drop AJAX)
        Route::post('cours/{cours}/lessons/reordonner', [LessonAdminController::class, 'reordonner'])->name('cours.lessons.reordonner');

        // ═══ ROUTES D'AFFILIATION ═══
        Route::prefix('affiliate')->name('affiliate.')->group(function () {
            
            // Dashboard principal
            Route::get('/', [AffiliateAdminController::class, 'index'])->name('index');
            
            // Parrainages
            Route::get('/referrals/pending', [AffiliateAdminController::class, 'pendingReferrals'])->name('referrals.pending');
            
            Route::post('/referrals/{referral}/complete', [AffiliateAdminController::class, 'completeReferral'])
                ->name('referrals.complete');
            
            Route::post('/referrals/{referral}/reject', [AffiliateAdminController::class, 'rejectReferral'])
                ->name('referrals.reject');
            
            Route::post('/referrals/complete-all', [AffiliateAdminController::class, 'completeAllPending'])
                ->name('referrals.complete-all');
            
            // Affiliés
            Route::get('/affiliates', [AffiliateAdminController::class, 'affiliatesList'])
                ->name('affiliates.list');
            
            Route::get('/affiliates/{user}', [AffiliateAdminController::class, 'affiliateDetail'])
                ->name('affiliates.detail');
            
            Route::post('/affiliates/{user}/deactivate', [AffiliateAdminController::class, 'deactivateAffiliate'])
                ->name('affiliates.deactivate');
            
            Route::post('/affiliates/{user}/activate', [AffiliateAdminController::class, 'activateAffiliate'])
                ->name('affiliates.activate');
            
            Route::get('/affiliates/{user}/referrals', [AffiliateAdminController::class, 'manageAffiliateReferrals'])
                ->name('affiliates.referrals');
            
            // Retraits
            Route::get('/withdrawals', [AffiliateAdminController::class, 'withdrawals'])
                ->name('withdrawals');
            
            Route::post('/withdrawals/approve', [AffiliateAdminController::class, 'approveWithdrawal'])
                ->name('withdrawals.approve');
            
            // Commission manuelle
            Route::post('/commission/add-manual', [AffiliateAdminController::class, 'addManualCommission'])
                ->name('commission.manual');
            
            // Export
            Route::get('/export', [AffiliateAdminController::class, 'exportStats'])
                ->name('export');
        });
    });
 

    //Instructor
    Route::prefix('instructor')->name('instructor.')->middleware(['auth'])->group(function(){
        Route::get('/',[CourseInstructorController::class,'index'])->name('dashboard');
        Route::get('instructor/create',[CourseInstructorController::class,'create'])->name('courses.create');
        Route::post('instructor',[CourseInstructorController::class,'store'])->name('courses.store');
        Route::get('instructor/{course}/edit',[CourseInstructorController::class,'edit'])->name('courses.edit');
        Route::put('instructor/{course}',[CourseInstructorController::class,'update'])->name('courses.update');
        Route::delete('instructor/{course}',[CourseInstructorController::class,'destroy'])->name('courses.destroy');
        Route::get('instructor/{course}',[CourseInstructorController::class,'show'])->name('courses.show');
 
        // Cours (uniquement les siens)
 
        // Leçons (uniquement dans ses cours)
        Route::resource('cours.lessons', LessonInstructorController::class);
 
         // Réordonner leçons
        Route::post('cours/{cours}/lessons/reordonner',[LessonInstructorController::class, 'reordonner']
        )->name('cours.lessons.reordonner');
    });
        
    // student
    Route::prefix('results')->name('student.')->group(function () {
        
        // Liste de tous les examens passés
        Route::get('/', [StudentResultController::class, 'index'])->name('index');
        
        // Résultats pour un examen spécifique (TCF, TEF, etc.)
        Route::get('/exam/{langue}', [StudentResultController::class, 'showExam'])->name('show')->middleware('can:view result');
        
        // Détails complets d'un passage
        Route::get('/passage/{passage}', [StudentResultController::class, 'showPassage'])->name('detail')->middleware('can:view result');

        Route::get('/langues',[LangueEpreuveController::class, 'index'])->name('langues.index');

        Route::get('/student/courses', [CourseDashboardController::class, 'index'])->name('course.index');
        Route::get('/student-progress/{user}',[StudentProgressController::class, 'show'])->name('student-progress.show');
    });

    Route::middleware(['auth'])->prefix('mon-parcours')->name('progression.')->group(function () {
        Route::get('/',           [ProgressionController::class, 'index'])->name('index');
        Route::get('/cours/{cours}', [ProgressionController::class, 'cours'])->name('cours');
        Route::get('/lecon/{lecon}', [ProgressionController::class, 'lecon'])->name('lecon');
    });


});
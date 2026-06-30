<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController; // 👈 Ajoute cet import
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserConsultationController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Instructor\CourseInstructorController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\ClassementController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\AnalyticsLangueController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminConsultationController;

// Routes publiques d'authentification pour Flutter
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/register', [RegisterController::class, 'register']); // 👈 Ajoute cette ligne
Route::post('/auth/forgot-password',[LoginController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [LoginController::class, 'resetPassword']);



// --- 🔒 Routes sécurisées par Token Sanctum (Pour Flutter) ---
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/auth/me',    [LoginController::class, 'me']);
    Route::post('/auth/logout',[LoginController::class, 'logout']);
    
    // Récupérer le profil : GET http://192.168.100.10:8000/api/profile
    Route::get('/profile', [ProfileController::class, 'edit']);
    
    // Modifier les infos de base : POST http://192.168.100.10:8000/api/profile/update
    Route::post('/profile/update', [ProfileController::class, 'update']);
    
    // Modifier le mot de passe : POST http://192.168.100.10:8000/api/profile/password
    Route::post('/profile/password', [ProfileController::class, 'updatePassword']);
    
    // Gérer l'avatar
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar']);

    // ── VISA (étudiant) ──────────────────────────────────
    Route::prefix('visa')->group(function () {
        Route::get   ('/demandes',                  [UserConsultationController::class, 'index']);
        Route::post  ('/demandes',                  [UserConsultationController::class, 'store']);
        Route::get   ('/demandes/{id}',             [UserConsultationController::class, 'show']);
        Route::put('/consultations/{consultation}',[UserConsultationController::class, 'update']);
        Route::post  ('/demandes/{id}/documents',   [UserConsultationController::class, 'uploadDocument']);
    });

    // ── COURS (étudiant) ─────────────────────────────────
    Route::prefix('etudiant')->group(function () {
        Route::get('/cours', [CourseController::class, 'index']);
        Route::get('/cours/{cours}', [CourseController::class, 'show']);
        Route::get('/cours/{course}/lessons/{lesson}', [CourseController::class, 'lesson']);
        Route::post('/lessons/{lesson}/validate', [CourseController::class, 'validateLesson']);
        // Route::get  ('/tests',                      [TestController::class, 'index']);
        // Route::get  ('/tests/{id}/resultats',       [TestController::class, 'resultats']);
        // Route::post ('/tests/{id}/soumettre',       [TestController::class, 'soumettre']);
        Route::get  ('/abonnement',                 [AbonnementController::class, 'show']);
        Route::post ('/abonnement/souscrire',       [AbonnementController::class, 'souscrire']);
        Route::post ('/abonnement/resilier',        [AbonnementController::class, 'resilier']);
    });

    // ── COURS PUBLIC ─────────────────────────────────────
    Route::get('/cours/{id}',                       [CourseController::class, 'show']);

    // ── CLASSEMENT ───────────────────────────────────────
    //Route::get('/classement',                       [ClassementController::class, 'index']);

    // ── MESSAGES ─────────────────────────────────────────
    Route::prefix('messages')->group(function () {
        // Route::get ('/conversations',               [MessageController::class, 'conversations']);
        // Route::get ('/conversations/{id}',          [MessageController::class, 'messages']);
        // Route::post('/conversations/{id}',          [MessageController::class, 'envoyer']);
    });

    // ── PROFESSEUR ───────────────────────────────────────
    Route::middleware('role:professeur')->prefix('professeur')->group(function () {
        Route::get   ('/cours',                     [CourseInstructorController::class, 'index']);
        Route::post  ('/cours',                     [CourseInstructorController::class, 'store']);
        Route::put   ('/cours/{id}',                [CourseInstructorController::class, 'update']);
        Route::patch ('/cours/{id}/publier',        [CourseInstructorController::class, 'publier']);
        Route::post  ('/cours/{id}/lecons',         [CourseInstructorController::class, 'creerLecon']);
        // Route::get   ('/apprenants',                [ProfApprenantController::class, 'index']);
        // Route::get   ('/apprenants/{id}',           [ProfApprenantController::class, 'show']);
        // Route::post  ('/commentaires',              [CommentaireController::class, 'store']);
    });

    // ── ADMIN ────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get   ('/utilisateurs',              [UserController::class, 'index']);
        Route::patch ('/utilisateurs/{id}/role',    [UserController::class, 'changerRole']);
        Route::patch ('/utilisateurs/{id}/suspendre',[UserController::class, 'suspendre']);
        Route::delete('/utilisateurs/{id}',         [UserController::class, 'destroy']);

        Route::get   ('/cours',                     [CourseController::class, 'index']);
        Route::delete('/cours/{id}',                [CourseController::class, 'destroy']);

        Route::get   ('/visa/demandes',             [AdminConsultationController::class, 'index']);
        Route::patch ('/visa/demandes/{id}/statut', [AdminConsultationController::class, 'changerStatut']);

        Route::get   ('/abonnements',               [AbonnementController::class, 'index']);

        Route::get   ('/statistiques',              [AnalyticsLangueController::class, 'index']);
        Route::get   ('/statistiques/revenus',      [AnalyticsLangueController::class, 'revenus']);
        Route::get   ('/statistiques/inscriptions', [AnalyticsLangueController::class, 'inscriptions']);
    });
});
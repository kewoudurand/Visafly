<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\Tcf\TcfController;
use App\Http\Controllers\Tcf\TcfEpreuveController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/consultation', [ConsultationController::class, 'create'])->name('consultation');
Route::post('/consultation', [ConsultationController::class, 'store'])->name('consultation.store');



Route::prefix('tcf')->name('tcf.')->middleware(['auth'])->group(function () {

    Route::get('/',                              [TcfController::class, 'index'])       ->name('index');
    Route::get('/serie/{serie}',                 [TcfController::class, 'disciplines']) ->name('disciplines');
    Route::post('/demarrer/{discipline}',        [TcfController::class, 'demarrer'])    ->name('demarrer');
    // Route::get('/examen/tcf/{serie:code}/{discipline:code}/{question?}', [TcfEpreuveController::class, 'show'])     ->name('epreuve');
    Route::get('/passage/{passage}/{question?}', [TcfEpreuveController::class, 'show'])->whereNumber('question')     ->name('epreuve');
    Route::post('/passage/{passage}/repondre',   [TcfEpreuveController::class, 'repondre']) ->name('repondre');
    Route::get('/passage/{passage}/terminer',    [TcfEpreuveController::class, 'terminer']) ->name('terminer');
    Route::get('/passage/{passage}/resultat',    [TcfEpreuveController::class, 'resultat']) ->name('resultat');

    // ✅ Route abonnement ajoutée
    Route::get('/abonnement', [TcfController::class, 'abonnement'])->name('abonnement');

});
Route::middleware(['role:admin'])->group(function () {
    // Pour l’admin :
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/User', [AdminController::class, 'user'])->name('admin.userAdd');
    Route::delete('/admin/User/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.destroy');
    Route::get('/admin/consultations/{id}', [AdminController::class, 'showConsultation'])->name('admin.seeUser');
    Route::delete('/admin/consultations/{id}', [AdminController::class, 'deleteConsultation'])->name('admin.delete');
    Route::post('/admin/consultations/{id}/statut', [AdminController::class, 'finish'])->name('admin.statut');
    Route::get('/admin/consultations/{id}/pdf', [AdminController::class, 'consultationPdf'])->name('admin.consultations.pdf');
});

Route::middleware(['role:user'])->group(function () {
    // routes réservées à la secrétaire
});
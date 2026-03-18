<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ConsultationController;

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

// Pour l’admin :
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/User', [AdminController::class, 'user'])->name('admin.userAdd');
Route::delete('/admin/User/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.destroy');
Route::get('/admin/consultations/{id}', [AdminController::class, 'showConsultation'])->name('admin.seeUser');
Route::delete('/admin/consultations/{id}', [AdminController::class, 'deleteConsultation'])->name('admin.delete');
Route::post('/admin/consultations/{id}/statut', [AdminController::class, 'finish'])->name('admin.statut');
Route::get('/admin/consultations/{id}/pdf', [AdminController::class, 'consultationPdf'])->name('admin.consultations.pdf');

// TCF
Route::prefix('tcf')->name('tcf.')->group(function () {
    Route::get('/', [TcfController::class, 'index'])->name('index');
    Route::get('/epreuve/{type}', [TcfController::class, 'epreuve'])->name('epreuve');
});

Route::middleware(['role:admin'])->group(function () {
    // routes réservées à l’admin
});

Route::middleware(['role:secretaire'])->group(function () {
    // routes réservées à la secrétaire
});
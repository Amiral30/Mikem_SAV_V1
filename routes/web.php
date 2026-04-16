<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MissionController as AdminMissionController;
use App\Http\Controllers\Admin\MissionValidationController;
use App\Http\Controllers\Admin\TechnicienController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Technicien\DashboardController as TechDashboardController;
use App\Http\Controllers\Technicien\MissionController as TechMissionController;
use App\Http\Controllers\Technicien\RapportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('missions', AdminMissionController::class);
    Route::get('/techniciens/{technicien}/export-pdf', [TechnicienController::class, 'exportPdf'])->name('techniciens.export');
    Route::resource('techniciens', TechnicienController::class);

    // V13 : Validation des rapports
    Route::post('/missions/{mission}/valider', [MissionValidationController::class, 'valider'])->name('missions.valider');
    Route::post('/missions/{mission}/rejeter', [MissionValidationController::class, 'rejeter'])->name('missions.rejeter');

    // Profil Admin (changement de mot de passe)
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('technicien')->middleware(['auth', 'technicien', 'check.onboarding'])->name('technicien.')->group(function () {
    // Flux d'Onboarding (Vérification code & Mot de passe)
    Route::get('/onboarding/verify', [\App\Http\Controllers\Technicien\OnboardingController::class, 'showVerify'])->name('onboarding.show');
    Route::post('/onboarding/verify', [\App\Http\Controllers\Technicien\OnboardingController::class, 'processVerify'])->name('onboarding.verify');
    Route::post('/onboarding/resend', [\App\Http\Controllers\Technicien\OnboardingController::class, 'resendCode'])->name('onboarding.resend');
    Route::get('/onboarding/password', [\App\Http\Controllers\Technicien\OnboardingController::class, 'showPassword'])->name('onboarding.password');
    Route::post('/onboarding/password', [\App\Http\Controllers\Technicien\OnboardingController::class, 'processPassword'])->name('onboarding.update-password');

    Route::get('/dashboard', [TechDashboardController::class, 'index'])->name('dashboard');
    Route::get('/missions', [TechMissionController::class, 'index'])->name('missions.index');
    Route::get('/missions/{mission}', [TechMissionController::class, 'show'])->name('missions.show');
    Route::patch('/missions/{mission}/accept', [TechMissionController::class, 'accept'])->name('missions.accept');
    Route::patch('/missions/{mission}/statut', [TechMissionController::class, 'updateStatut'])->name('missions.statut');
    Route::get('/missions/{mission}/rapport', [RapportController::class, 'create'])->name('rapports.create');
    Route::post('/missions/{mission}/rapport', [RapportController::class, 'store'])->name('rapports.store');
    Route::get('/missions/{mission}/rapport/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
    Route::get('/missions/{mission}/rapport/{rapport}/edit', [RapportController::class, 'edit'])->name('rapports.edit');
    Route::put('/missions/{mission}/rapport/{rapport}', [RapportController::class, 'update'])->name('rapports.update');
    Route::get('/historique', [TechMissionController::class, 'historique'])->name('historique');

    // Profil Technicien
    Route::get('/profile', [\App\Http\Controllers\Technicien\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\Technicien\ProfileController::class, 'update'])->name('profile.update');
});

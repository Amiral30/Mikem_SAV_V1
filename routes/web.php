<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MissionController as AdminMissionController;
use App\Http\Controllers\Admin\TechnicienController;
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
    Route::patch('/missions/{mission}/statut', [AdminMissionController::class, 'updateStatut'])->name('missions.statut');
    Route::resource('techniciens', TechnicienController::class);
});

Route::prefix('technicien')->middleware(['auth', 'technicien'])->name('technicien.')->group(function () {
    Route::get('/dashboard', [TechDashboardController::class, 'index'])->name('dashboard');
    Route::get('/missions', [TechMissionController::class, 'index'])->name('missions.index');
    Route::get('/missions/{mission}', [TechMissionController::class, 'show'])->name('missions.show');
    Route::patch('/missions/{mission}/accept', [TechMissionController::class, 'accept'])->name('missions.accept');
    Route::patch('/missions/{mission}/statut', [TechMissionController::class, 'updateStatut'])->name('missions.statut');
    Route::get('/missions/{mission}/rapport', [RapportController::class, 'create'])->name('rapports.create');
    Route::post('/missions/{mission}/rapport', [RapportController::class, 'store'])->name('rapports.store');
    Route::get('/missions/{mission}/rapport/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
    Route::get('/historique', [TechMissionController::class, 'historique'])->name('historique');
});

<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Committee\TpsController;
use App\Http\Controllers\Student\VotingController;
use Illuminate\Support\Facades\Route;

// Zero-Guest Policy: Akses root langsung dialihkan ke login
Route::redirect('/', '/login');

// Autentikasi Terpadu (Single Unified Entry Point)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Area Mahasiswa (Bilik Suara)
Route::middleware(['auth:student'])->prefix('voting')->name('student.')->group(function () {
    Route::get('/', [VotingController::class, 'index'])->name('voting');
    Route::post('/submit', [VotingController::class, 'store'])->name('voting.store');
    Route::get('/selesai', [VotingController::class, 'completed'])->name('completed');
    Route::get('/tanda-terima', [VotingController::class, 'receipt'])->name('receipt');
});

// Area Administrator KPUM
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/periods/{period}/toggle-publish', [DashboardController::class, 'togglePublish'])->name('periods.toggle-publish');
    Route::post('/periods/{period}/toggle-status', [DashboardController::class, 'toggleStatus'])->name('periods.toggle-status');
    Route::resource('candidates', \App\Http\Controllers\Admin\CandidateController::class);
});

// Area Panitia TPS Onsite
Route::middleware(['auth:admin', 'committee'])->prefix('committee')->name('committee.')->group(function () {
    Route::get('/dashboard', [TpsController::class, 'index'])->name('dashboard');
    Route::post('/verifikasi/{participation}', [TpsController::class, 'verify'])->name('tps.verify');
});

<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Student\VotingController;
use Illuminate\Support\Facades\Route;

// Zero-Guest Policy: Akses root langsung dialihkan ke login bilik suara mahasiswa
Route::redirect('/', '/login');

// Autentikasi Mahasiswa Pemilih (Bilik Suara Digital)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Autentikasi Khusus Administrator KPUM
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Area Mahasiswa (Bilik Suara)
Route::middleware(['auth:student'])->prefix('voting')->name('student.')->group(function () {
    Route::get('/', [VotingController::class, 'index'])->name('voting');
    Route::get('/kandidat/{candidate}', [VotingController::class, 'showCandidate'])->name('candidate.detail');
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

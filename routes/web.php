<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VoterController;
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

// Area Administrator KPUM (Panel Pengelolaan Terstruktur)
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard Utama: Kilasan / Executive Overview
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen Pemilihan: Kelola Periode Pemilihan (Multi-Period System)
    Route::get('/periods', [PeriodController::class, 'index'])->name('periods.index');
    Route::post('/periods', [PeriodController::class, 'store'])->name('periods.store');
    Route::put('/periods/{period}', [PeriodController::class, 'update'])->name('periods.update');
    Route::delete('/periods/{period}', [PeriodController::class, 'destroy'])->name('periods.destroy');
    Route::post('/periods/{period}/set-active', [PeriodController::class, 'setActive'])->name('periods.set-active');
    Route::post('/periods/{period}/toggle-voting', [PeriodController::class, 'toggleVoting'])->name('periods.toggle-voting');
    Route::post('/periods/switch', [PeriodController::class, 'switchPeriod'])->name('periods.switch');

    // 3. Manajemen Pemilihan: Kelola Paslon & Visi Misi
    Route::resource('candidates', CandidateController::class);

    // 4. Manajemen Pemilih: Data Mahasiswa, DPT & Kehadiran
    Route::get('/voters', [VoterController::class, 'index'])->name('voters.index');

    // 5. Pelaksanaan Pemilihan: Monitoring Suara & Hasil Pemilihan
    Route::get('/elections/monitoring', [ElectionController::class, 'monitoring'])->name('elections.monitoring');
    Route::get('/elections/results', [ElectionController::class, 'results'])->name('elections.results');
    Route::get('/elections/live-data', [ElectionController::class, 'liveData'])->name('elections.live-data');

    // 6. Keamanan & Aktivitas: Audit Trail & Log Aktivitas
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');

    // 7. Pengaturan Akun & Sistem (SETTING)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
});

<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Zero-Guest Policy: Akses root langsung dialihkan ke login
Route::redirect('/', '/login');

// Autentikasi Terpadu (Single Unified Entry Point)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

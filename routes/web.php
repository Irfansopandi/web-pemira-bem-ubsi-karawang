<?php

use Illuminate\Support\Facades\Route;

// Zero-Guest Policy: Root directly redirects to Login
Route::redirect('/', '/login');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

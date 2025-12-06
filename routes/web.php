<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifyController;

Route::get('/', function () {
    return view('landing');
});


// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::post('/logout', [GoogleController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::view('/verifikasi', 'landing')->name('verifikasi');



Route::get('/verifikasi', [VerifyController::class, 'index'])->name('verifikasi');
Route::post('/verifikasi/check', [VerifyController::class, 'check'])->name('verifikasi.check');

// halaman hasil
Route::get('/verifikasi/valid', [VerifyController::class, 'valid'])->name('verifikasi.valid');
Route::get('/verifikasi/invalid', [VerifyController::class, 'invalid'])->name('verifikasi.invalid');


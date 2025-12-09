<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OnboardingController;

/*
|--------------------------------------------------------------------------
| Landing / Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Email + Wallet + Google)
|--------------------------------------------------------------------------
*/

// Halaman login utama
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

// Login Email
Route::post('/login/email', [LoginController::class, 'loginEmail'])
    ->middleware('guest')
    ->name('login.email');

// Login Wallet (dipanggil JS/metamask)
Route::post('/login/wallet', [LoginController::class, 'loginWallet'])
    ->middleware('guest')
    ->name('login.wallet');

// Login Google
Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->middleware('guest')
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->middleware('guest')
    ->name('google.callback');

// Logout (setelah login)
Route::post('/logout', [GoogleController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| REGISTER (Pengguna & Lembaga – multi-tab)
|--------------------------------------------------------------------------
*/

// Halaman form register (tab Pengguna & Lembaga)
Route::get('/register', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('register');

// Proses submit register PENGGUNA (tab Pengguna)
Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('guest')
    ->name('register.store');

// Proses submit register LEMBAGA (tab Lembaga)
// NOTE: path dan name HARUS sama dengan yang dipakai di view:
// action="{{ route('register.lembaga.store') }}"
Route::post('/register/lembaga', [RegisterController::class, 'storeLembaga'])
    ->middleware('guest')
    ->name('register.lembaga.store');

/*
|--------------------------------------------------------------------------
| Protected Routes (Hanya jika login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Onboarding - complete profile after Google/Wallet login
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('/onboarding/skip', [OnboardingController::class, 'skip'])->name('onboarding.skip');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Verifikasi Sertifikat
|--------------------------------------------------------------------------
*/

// Halaman input kode hash verifikasi
Route::get('/verifikasi', [VerifyController::class, 'index'])->name('verifikasi');

// Submit form cek hash (untuk AJAX)
Route::post('/verifikasi/check', [VerifyController::class, 'check'])->name('verifikasi.check');

// Halaman hasil dengan hash di URL
Route::get('/verifikasi/{hash}', [VerifyController::class, 'show'])->name('verifikasi.show');

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/

Route::get('/checkout/{slug}', [PaymentController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [PaymentController::class, 'process'])->name('checkout.process');
Route::get('/payment/success/{orderNumber}', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/contact-enterprise', [PaymentController::class, 'contactEnterprise'])->name('contact.enterprise');
Route::post('/contact-enterprise', [PaymentController::class, 'sendContactEnterprise'])->name('contact.enterprise.send');

/*
|--------------------------------------------------------------------------
| Support Pages
|--------------------------------------------------------------------------
*/

Route::get('/bantuan', [\App\Http\Controllers\PageController::class, 'bantuan'])->name('bantuan');
Route::get('/dokumentasi', [\App\Http\Controllers\PageController::class, 'dokumentasi'])->name('dokumentasi');
Route::get('/status', [\App\Http\Controllers\PageController::class, 'status'])->name('status');
Route::get('/kontak', [\App\Http\Controllers\PageController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [\App\Http\Controllers\PageController::class, 'sendKontak'])->name('kontak.send');

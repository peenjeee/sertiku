<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;

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

    // Dashboard - redirect based on account type
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // If profile not completed, go to onboarding
        if (! $user->isProfileCompleted()) {
            return redirect()->route('onboarding');
        }

        // Redirect based on account type
        if ($user->isInstitution()) {
            return redirect()->route('lembaga.dashboard');
        }

        // Personal users stay on this dashboard
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
Route::post('/payment/quick-upgrade', [PaymentController::class, 'quickUpgrade'])->middleware('auth')->name('payment.quick-upgrade');
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

// Legal Pages
Route::get('/privasi', [\App\Http\Controllers\PageController::class, 'privasi'])->name('privasi');
Route::get('/syarat', [\App\Http\Controllers\PageController::class, 'syarat'])->name('syarat');
Route::get('/cookie', [\App\Http\Controllers\PageController::class, 'cookie'])->name('cookie');

/*
|--------------------------------------------------------------------------
| Lembaga Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('lembaga')->name('lembaga.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\LembagaController::class, 'dashboard'])->name('dashboard');

    // Sertifikat
    Route::get('/sertifikat', [\App\Http\Controllers\LembagaController::class, 'indexSertifikat'])->name('sertifikat.index');
    Route::get('/sertifikat/create', [\App\Http\Controllers\LembagaController::class, 'createSertifikat'])->name('sertifikat.create');
    Route::post('/sertifikat', [\App\Http\Controllers\LembagaController::class, 'storeSertifikat'])->name('sertifikat.store');

    // Template
    Route::get('/template', [\App\Http\Controllers\LembagaController::class, 'indexTemplate'])->name('template.index');
    Route::get('/template/upload', [\App\Http\Controllers\LembagaController::class, 'uploadTemplate'])->name('template.upload');
    Route::post('/template', [\App\Http\Controllers\LembagaController::class, 'storeTemplate'])->name('template.store');
});

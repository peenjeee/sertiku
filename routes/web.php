<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\BulkCertificateController;

/*
|--------------------------------------------------------------------------
| Landing / Public
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| MASTER AUTH & DASHBOARD
|--------------------------------------------------------------------------
*/

// Master Login (separate login page)
Route::get('/master/login', [\App\Http\Controllers\Auth\MasterLoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('master.login');

Route::post('/master/login', [\App\Http\Controllers\Auth\MasterLoginController::class, 'login'])
    ->middleware('guest')
    ->name('master.login.submit');

Route::post('/master/logout', [\App\Http\Controllers\Auth\MasterLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('master.logout');

// Master Dashboard Routes (protected by master.only middleware)
Route::prefix('master')->name('master.')->middleware(['auth', 'master.only'])->group(function () {

    // Dashboard root
    Route::get('/', [\App\Http\Controllers\MasterController::class, 'dashboard'])->name('dashboard');

    // Manage Admins
    Route::get('/admins', [\App\Http\Controllers\MasterController::class, 'manageAdmins'])->name('admins');
    Route::post('/admins/{user}/promote', [\App\Http\Controllers\MasterController::class, 'promoteToAdmin'])->name('admins.promote');
    Route::post('/admins/{user}/demote', [\App\Http\Controllers\MasterController::class, 'demoteAdmin'])->name('admins.demote');

    // Blockchain Wallet
    Route::get('/blockchain', [\App\Http\Controllers\MasterController::class, 'blockchain'])->name('blockchain');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\MasterController::class, 'settings'])->name('settings');

    // Logs
    Route::get('/logs', [\App\Http\Controllers\MasterController::class, 'logs'])->name('logs');

    // Support Tickets
    Route::get('/support', [\App\Http\Controllers\SupportController::class, 'masterIndex'])->name('support');
    Route::get('/support/{ticket}', [\App\Http\Controllers\SupportController::class, 'masterShow'])->name('support.show');
    Route::post('/support/{ticket}/reply', [\App\Http\Controllers\SupportController::class, 'adminReply'])->name('support.reply');
});


Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');

// SEO Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');



Route::get('/api-docs', function () {
    return view('pages.api');
})->name('api.docs');

// Lead Collection (CTA form on landing page -> n8n webhook)
Route::post('/leads/subscribe-cta', [\App\Http\Controllers\LeadController::class, 'subscribeCta'])
    ->name('leads.subscribe-cta');

// Status Notification Subscription (status page -> n8n webhook)
Route::post('/leads/subscribe-status', [\App\Http\Controllers\LeadController::class, 'subscribeStatus'])
    ->name('leads.subscribe-status');

// Contact Form Submission (kontak page -> n8n webhook)
Route::post('/kontak/send', [\App\Http\Controllers\ContactController::class, 'send'])
    ->name('kontak.send');

// Enterprise Form Submission (enterprise page -> n8n webhook + email)
Route::post('/contact/enterprise/send', [\App\Http\Controllers\EnterpriseController::class, 'send'])
    ->name('contact.enterprise.send');

// Temporary route removed

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
| MASTER AUTH & DASHBOARD
|--------------------------------------------------------------------------
*/



// Password Reset
Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION OTP
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;

// OTP Verification Page
Route::get('/verify-email', [EmailVerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.otp');

Route::get('/email/verify', function () {
    return redirect()->route('verification.otp');
})->middleware('auth')->name('verification.notice');

// Verify OTP
Route::post('/verify-email', [EmailVerificationController::class, 'verify'])
    ->middleware('auth')
    ->name('verification.otp.verify');

// Resend OTP
Route::post('/verify-email/resend', [EmailVerificationController::class, 'resend'])
    ->middleware('auth')
    ->name('verification.otp.resend');

// Email Input (for wallet users)
Route::get('/verify-email/input', [EmailVerificationController::class, 'showEmailInput'])
    ->middleware('auth')
    ->name('verification.email.input');

Route::post('/verify-email/input', [EmailVerificationController::class, 'storeEmail'])
    ->middleware('auth')
    ->name('verification.email.store');

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
    // Route for Feedback
    Route::get('/feedback', [\App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');

    // Onboarding - complete profile after Google/Wallet login
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
        Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
        Route::get('/onboarding/skip', [OnboardingController::class, 'skip'])->name('onboarding.skip');
    });

    // Dashboard - redirect based on account type
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Check email verification first (skip for @sertiku.web.id)
        if (!$user->email_verified_at && !str_ends_with($user->email ?? '', '@sertiku.web.id')) {
            // Wallet users with dummy email go to email input
            if (str_ends_with($user->email, '@wallet.local')) {
                return redirect()->route('verification.email.input');
            }
            return redirect()->route('verification.otp');
        }

        // If profile not completed, go to onboarding
        if (!$user->isProfileCompleted()) {
            return redirect()->route('onboarding');
        }

        // Redirect based on account type
        if ($user->is_master) {
            return redirect()->route('master.dashboard');
        }

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isInstitution()) {
            return redirect()->route('lembaga.dashboard');
        }

        // Personal users go to user dashboard
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/avatar', [\App\Http\Controllers\SettingsController::class, 'updateAvatar'])->name('settings.avatar');
    Route::delete('/settings/avatar', [\App\Http\Controllers\SettingsController::class, 'removeAvatar'])->name('settings.avatar.remove');
    Route::put('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/account', [\App\Http\Controllers\SettingsController::class, 'deleteAccount'])->name('settings.delete');

    // Lembaga (Institution) Routes - Only for lembaga accounts
    Route::prefix('lembaga')->name('lembaga.')->middleware(['lembaga.only', 'verified'])->group(function () {
        // Dashboard
        Route::get('/', [\App\Http\Controllers\LembagaController::class, 'dashboard'])->name('dashboard');

        // Certificates
        // Bulk Upload (Must be before wildcards)
        Route::get('/sertifikat/bulk', [BulkCertificateController::class, 'index'])->name('sertifikat.bulk');
        Route::post('/sertifikat/bulk', [BulkCertificateController::class, 'store'])->name('sertifikat.bulk.store');

        Route::get('/sertifikat', [\App\Http\Controllers\LembagaController::class, 'indexSertifikat'])->name('sertifikat.index');
        Route::get('/sertifikat/create', [\App\Http\Controllers\LembagaController::class, 'createSertifikat'])->name('sertifikat.create');
        Route::post('/sertifikat', [\App\Http\Controllers\LembagaController::class, 'storeSertifikat'])->name('sertifikat.store');
        Route::get('/sertifikat/{certificate}', [\App\Http\Controllers\LembagaController::class, 'showSertifikat'])->name('sertifikat.show');
        Route::post('/sertifikat/{certificate}/revoke', [\App\Http\Controllers\LembagaController::class, 'revokeSertifikat'])->name('sertifikat.revoke');
        Route::post('/sertifikat/{certificate}/reactivate', [\App\Http\Controllers\LembagaController::class, 'reactivateSertifikat'])->name('sertifikat.reactivate');

        // Templates
        Route::get('/template', [\App\Http\Controllers\LembagaController::class, 'indexTemplate'])->name('template.index');
        Route::get('/template/upload', [\App\Http\Controllers\LembagaController::class, 'uploadTemplate'])->name('template.upload');
        Route::post('/template', [\App\Http\Controllers\LembagaController::class, 'storeTemplate'])->name('template.store');
        Route::delete('/template/{template}', [\App\Http\Controllers\LembagaController::class, 'destroyTemplate'])->name('template.destroy');
        Route::post('/template/{template}/toggle', [\App\Http\Controllers\LembagaController::class, 'toggleTemplate'])->name('template.toggle');

        // Settings/Pengaturan
        Route::get('/pengaturan', [\App\Http\Controllers\LembagaController::class, 'settings'])->name('settings');
        Route::put('/pengaturan/profile', [\App\Http\Controllers\LembagaController::class, 'updateProfile'])->name('settings.update');
        Route::post('/pengaturan/avatar', [\App\Http\Controllers\LembagaController::class, 'updateAvatar'])->name('settings.avatar');
        Route::delete('/pengaturan/avatar', [\App\Http\Controllers\LembagaController::class, 'removeAvatar'])->name('settings.avatar.remove');
        Route::put('/pengaturan/password', [\App\Http\Controllers\LembagaController::class, 'updatePassword'])->name('settings.password');
        Route::post('/pengaturan/document', [\App\Http\Controllers\LembagaController::class, 'updateDocument'])->name('settings.document.update');
        Route::delete('/pengaturan/document', [\App\Http\Controllers\LembagaController::class, 'deleteDocument'])->name('settings.document.delete');

        // Activity Log
        Route::get('/activity-log', [\App\Http\Controllers\LembagaController::class, 'activityLog'])->name('activity-log');
    });

    // User (Personal) Routes - Only for personal (pengguna) accounts
    Route::prefix('user')->name('user.')->middleware(['pengguna.only', 'verified'])->group(function () {
        // Dashboard
        Route::get('/', [\App\Http\Controllers\UserController::class, 'dashboard'])->name('dashboard');

        // Sertifikat Saya
        Route::get('/sertifikat', [\App\Http\Controllers\UserController::class, 'sertifikat'])->name('sertifikat');

        // Profil
        Route::get('/profil', [\App\Http\Controllers\UserController::class, 'profil'])->name('profil');
        Route::get('/profil/edit', [\App\Http\Controllers\UserController::class, 'editProfil'])->name('profil.edit');
        Route::put('/profil', [\App\Http\Controllers\UserController::class, 'updateProfil'])->name('profil.update');
        Route::put('/profil/password', [\App\Http\Controllers\UserController::class, 'updatePassword'])->name('profil.password');
        Route::post('/profil/avatar', [\App\Http\Controllers\UserController::class, 'uploadAvatar'])->name('profil.avatar');
        Route::delete('/profil/avatar', [\App\Http\Controllers\UserController::class, 'removeAvatar'])->name('profil.avatar.remove');

        // Notifikasi
        Route::get('/notifikasi', [\App\Http\Controllers\UserController::class, 'notifikasi'])->name('notifikasi');
        Route::post('/notifikasi/{id}/read', [\App\Http\Controllers\UserController::class, 'markAsRead'])->name('notifikasi.read');
        Route::post('/notifikasi/read-all', [\App\Http\Controllers\UserController::class, 'markAllAsRead'])->name('notifikasi.readAll');
    });
});

/*
|--------------------------------------------------------------------------
| Contact Admin (Support Tickets Page)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/contact-admin', [\App\Http\Controllers\SupportController::class, 'contactAdmin'])->name('contact.admin');
    Route::post('/contact-admin/create', [\App\Http\Controllers\SupportController::class, 'createTicketWeb'])->name('contact.admin.create');
    Route::get('/contact-admin/{ticket}', [\App\Http\Controllers\SupportController::class, 'showTicket'])->name('contact.admin.show');
    Route::post('/contact-admin/{ticket}/send', [\App\Http\Controllers\SupportController::class, 'sendMessageWeb'])->name('contact.admin.send');
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
Route::post('/verifikasi/file', [\App\Http\Controllers\VerifyController::class, 'verifyFile'])->name('verifikasi.file');
Route::get('/verifikasi/{hash}', [\App\Http\Controllers\VerifyController::class, 'show'])->name('verifikasi.show');
Route::get('/verifikasi/{hash}/pdf', [\App\Http\Controllers\VerifyController::class, 'downloadPdf'])->name('verifikasi.pdf');

// Fraud Report - Laporan Pemalsuan Sertifikat
Route::post('/lapor-pemalsuan', [VerifyController::class, 'storeFraudReport'])->name('fraud.report.store');

/*
|--------------------------------------------------------------------------
| Blockchain Verification
|--------------------------------------------------------------------------
*/

Route::get('/blockchain/verify', [\App\Http\Controllers\BlockchainController::class, 'verify'])->name('blockchain.verify');
Route::get('/blockchain/api/verify', [\App\Http\Controllers\BlockchainController::class, 'verifyApi'])->name('blockchain.verify.api');

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/

Route::get('/checkout/{slug}', [PaymentController::class, 'checkout'])->middleware(['auth', 'lembaga.only'])->name('checkout');
Route::post('/checkout/process', [PaymentController::class, 'process'])->middleware(['auth', 'lembaga.only'])->name('checkout.process');
Route::post('/payment/quick-upgrade', [PaymentController::class, 'quickUpgrade'])->middleware(['auth', 'lembaga.only'])->name('payment.quick-upgrade');
Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment'])->middleware(['auth', 'lembaga.only'])->name('payment.confirm');
Route::get('/payment/success/{orderNumber}', [PaymentController::class, 'success'])->middleware('auth')->name('payment.success');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/contact-enterprise', [PaymentController::class, 'contactEnterprise'])->name('contact.enterprise');
Route::post('/contact-enterprise', [PaymentController::class, 'sendContactEnterprise'])->name('contact.enterprise.send');

/*
|--------------------------------------------------------------------------
| Support Pages
|--------------------------------------------------------------------------
*/

Route::get('/bantuan', [\App\Http\Controllers\PageController::class, 'bantuan'])->name('bantuan');
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



    // Bulk Upload (Professional & Enterprise Only)
    Route::middleware(['professional'])->group(function () {
        Route::get('/sertifikat/bulk', [BulkCertificateController::class, 'index'])->name('sertifikat.bulk');
        Route::post('/sertifikat/bulk', [BulkCertificateController::class, 'store'])->name('sertifikat.bulk.store');
        Route::get('/sertifikat/bulk/template-csv', [BulkCertificateController::class, 'downloadTemplateCsv'])->name('sertifikat.bulk.template-csv');
        Route::get('/sertifikat/bulk/template-xlsx', [BulkCertificateController::class, 'downloadTemplateXlsx'])->name('sertifikat.bulk.template-xlsx');

        // System Template Generator
        Route::get('/template/create', [\App\Http\Controllers\LembagaController::class, 'createTemplate'])->name('template.create');
        Route::post('/template/system', [\App\Http\Controllers\LembagaController::class, 'storeSystemTemplate'])->name('template.storeSystem');
    });

    // Template
    Route::get('/template', [\App\Http\Controllers\LembagaController::class, 'indexTemplate'])->name('template.index');
    Route::get('/template/upload', [\App\Http\Controllers\LembagaController::class, 'uploadTemplate'])->name('template.upload'); // User Upload
    Route::post('/template', [\App\Http\Controllers\LembagaController::class, 'storeTemplate'])->name('template.store');
    Route::get('/template/{template}/edit-position', [\App\Http\Controllers\LembagaController::class, 'editTemplatePosition'])->name('template.editPosition');
    Route::put('/template/{template}/position', [\App\Http\Controllers\LembagaController::class, 'updateTemplatePosition'])->name('template.updatePosition');

    // API Tokens (Professional & Enterprise only)
    Route::get('/api-tokens', [\App\Http\Controllers\ApiTokenController::class, 'index'])->name('api-tokens.index');
    Route::post('/api-tokens', [\App\Http\Controllers\ApiTokenController::class, 'store'])->name('api-tokens.store');
    Route::delete('/api-tokens/{tokenId}', [\App\Http\Controllers\ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\AdminController::class, 'analytics'])->name('analytics');

    // Kelola Pengguna
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [\App\Http\Controllers\AdminController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{user}/toggle', [\App\Http\Controllers\AdminController::class, 'toggleUser'])->name('users.toggle');

    // Backup & Restore
    Route::get('/backup', [\App\Http\Controllers\BackupController::class, 'index'])->name('backup');
    Route::post('/backup/create', [\App\Http\Controllers\BackupController::class, 'createBackup'])->name('backup.create');
    Route::get('/backup/download/{filename}', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{filename}', [\App\Http\Controllers\BackupController::class, 'delete'])->name('backup.delete');
    Route::post('/backup/restore', [\App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore');
    // Google Drive
    Route::post('/backup/drive/restore', [\App\Http\Controllers\BackupController::class, 'restoreFromDrive'])->name('backup.drive.restore');
    Route::delete('/backup/drive/delete', [\App\Http\Controllers\BackupController::class, 'deleteFromDrive'])->name('backup.drive.delete');
    Route::get('/backup/drive/auth', [\App\Http\Controllers\GoogleDriveAuthController::class, 'redirect'])->name('backup.drive.auth');
    Route::get('/backup/drive/callback', [\App\Http\Controllers\GoogleDriveAuthController::class, 'callback'])->name('backup.drive.callback');
    Route::post('/backup/drive/disconnect', [\App\Http\Controllers\GoogleDriveAuthController::class, 'disconnect'])->name('backup.drive.disconnect');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\AdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\AdminController::class, 'updatePassword'])->name('profile.password');
});



/*
|--------------------------------------------------------------------------
| Support Ticket Routes (User side - AJAX)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('support')->name('support.')->group(function () {
    Route::post('/ticket', [\App\Http\Controllers\SupportController::class, 'createTicket'])->name('ticket.create');
    Route::post('/message', [\App\Http\Controllers\SupportController::class, 'sendMessage'])->name('message.send');
    Route::get('/messages/{ticket}', [\App\Http\Controllers\SupportController::class, 'getMessages'])->name('messages');
    Route::get('/tickets', [\App\Http\Controllers\SupportController::class, 'userTickets'])->name('tickets');
    Route::post('/ticket/{ticket}/close', [\App\Http\Controllers\SupportController::class, 'closeTicket'])->name('ticket.close');
});

// Admin support routes (add to admin group)
Route::middleware(['auth', 'admin'])->prefix('admin/support')->name('admin.support.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SupportController::class, 'adminIndex'])->name('index');
    Route::get('/{ticket}', [\App\Http\Controllers\SupportController::class, 'adminShow'])->name('show');
    Route::post('/{ticket}/reply', [\App\Http\Controllers\SupportController::class, 'adminReply'])->name('reply');
});

// API for unread count
Route::middleware(['auth'])->get('/api/support/unread', [\App\Http\Controllers\SupportController::class, 'unreadCount']);

// Utility to fix storage link on hosting
Route::get('/fix-storage', function () {
    try {
        Artisan::call('storage:link');
        return 'Storage Link Created Successfully! <br> Template images should work now. <a href="/lembaga/template">Back to Gallery</a>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

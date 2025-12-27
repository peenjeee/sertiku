<?php

use App\Http\Controllers\Api\V1\CertificateApiController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Chat API (n8n integration)
Route::post('/chat', [ChatController::class, 'send'])->name('api.chat');

// API v1 Routes
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Public endpoints (no auth required)
    Route::get('/verify/{hash}', [CertificateApiController::class, 'verify'])->name('verify');
    Route::get('/stats', [CertificateApiController::class, 'stats'])->name('stats');

    // Protected endpoints (auth required via Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/certificates', [CertificateApiController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{id}', [CertificateApiController::class, 'show'])->name('certificates.show');
        Route::post('/certificates', [CertificateApiController::class, 'store'])->name('certificates.store');
        Route::put('/certificates/{id}/revoke', [CertificateApiController::class, 'revoke'])->name('certificates.revoke');
        Route::put('/certificates/{id}/reactivate', [CertificateApiController::class, 'reactivate'])->name('certificates.reactivate');
    });
});

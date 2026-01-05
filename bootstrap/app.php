<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'payment/callback',
            'payment/nowpayments/callback',
            'api/*',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.only' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'master.only' => \App\Http\Middleware\EnsureUserIsMaster::class,
            'lembaga.only' => \App\Http\Middleware\EnsureUserIsLembaga::class,
            'pengguna.only' => \App\Http\Middleware\EnsureUserIsPengguna::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'professional' => \App\Http\Middleware\CheckProfessionalPlan::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

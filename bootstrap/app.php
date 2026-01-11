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
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Token tidak valid atau tidak ada.',
                ], 401);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Endpoint tidak ditemukan.',
                ], 404);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode HTTP tidak diizinkan untuk endpoint ini.',
                ], 405);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Anda tidak memiliki izin.',
                ], 403);
            }
        });

        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Anda tidak memiliki wewenang untuk tindakan ini.',
                ], 403);
            }
        });

        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi Anda telah kedaluwarsa (CSRF Token mismatch). Silakan refresh atau login ulang.',
                ], 419);
            }
        });

        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.',
                ], 429);
            }
        });

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang diminta tidak ditemukan.',
                ], 404);
            }
        });

        $exceptions->render(function (\Illuminate\Foundation\Http\Exceptions\MaintenanceModeException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sistem sedang dalam perbaikan (Maintenance).',
                ], 503);
            }
        });

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang diberikan tidak valid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ukuran file yang diunggah terlalu besar.',
                ], 413);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Terjadi kesalahan pada permintaan HTTP.',
                ], $e->getStatusCode());
            }
        });

        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api') || $request->is('api/*')) {
                // Ensure we don't swallow successful non-errors if any, but this is default error handler.
                // We should only catch if it's not already handled.
                // However, renderable methods are for exceptions.
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan internal server.',
                    'debug' => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }
        });
    })->create();

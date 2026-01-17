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
            'onboarding.completed' => \App\Http\Middleware\EnsureOnboardingCompleted::class,
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

        // Handle Database Connection Errors for Web Requests
        $exceptions->render(function (\Illuminate\Database\ConnectionException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        $exceptions->render(function (\PDOException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        // Handle Redis Connection Errors for Web Requests
        $exceptions->render(function (\Predis\Connection\ConnectionException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        $exceptions->render(function (\RedisException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        // Specific handling for the error seen in screenshot
        $exceptions->render(function (\Predis\Connection\Resource\Exception\StreamInitException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        // Handle Doctrine DBAL Errors (if used for some database operations)
        $exceptions->render(function (\Doctrine\DBAL\Exception\ConnectionException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        $exceptions->render(function (\Doctrine\DBAL\Exception\ServerException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        // General Predis Exception (Parent of all Predis errors)
        $exceptions->render(function (\Predis\PredisException $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api') && !$request->is('api/*')) {
                return response()->view('errors.db_error', [], 503);
            }
        });

        // Catch-all for "Connection refused" in generic Exceptions if not caught above
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            $msg = strtolower($e->getMessage());
            $keywords = [
                'connection refused',
                'actively refused',
                'sqlstate[hy000]',
                'server has gone away',
                'no connection could be made',
                'target machine actively refused',
                'failed to connect',
                'network is unreachable',
                'timed out',
                'timeout',
                'connection timed out',
                'connection reset by peer',
                'topology was destroyed',
                'unknown database',
                'host mismatch',
                'no connection to the server',
                'lost connection',
                'is dead',
                'error while sending',
                'decryption failed',
                'server closed the connection',
                'ssl connection has been closed',
                'error writing data',
                'resource deadlock avoided',
                'transaction() on null',
                'child connection forced to terminate',
                'query_wait_timeout',
                'reset by peer',
                'physical connection is not usable',
                'tcp provider: error code',
                'communication link failure',
                'connection is no longer valid',
                'sqlstate[08006]', // Postgres connection failure
                'could not find driver',
                'writetimeout',
                'readtimeout',
                'php_network_getaddresses',
                'temporary failure in name resolution',
                'name or service not known',
                'getaddrinfo failed',
                'nodename nor servname provided',
                'socket error',
                'remote host closed connection',
                'unexpected eof',
                'error reading result set',
            ];

            if (!$request->is('api') && !$request->is('api/*')) {
                // Check Message String
                foreach ($keywords as $keyword) {
                    if (str_contains($msg, $keyword)) {
                        return response()->view('errors.db_error', [], 503);
                    }
                }

                // Check Error Codes for common DB connection issues (MySQL 2002)
                if ($e->getCode() === 2002) {
                    return response()->view('errors.db_error', [], 503);
                }
            }
        });

    })->create();

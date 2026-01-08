<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// TAMBAHKAN BARIS DI BAWAH INI
use Illuminate\Support\Facades\Schema;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate Limiter untuk Login
        RateLimiter::for('login', function (Request $request) {
            $key = $request->input('email') ?: $request->input('wallet_address') ?: $request->ip();

            return Limit::perMinute(5)->by($key . '|' . $request->ip())->response(function () {
                return response('Too many login attempts. Please try again in 60 seconds.', 429);
            });
        });

        // Sekarang ini akan berhasil
        Schema::defaultStringLength(191);
    }
}

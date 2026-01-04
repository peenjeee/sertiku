<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// TAMBAHKAN BARIS DI BAWAH INI
use Illuminate\Support\Facades\Schema;

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
        // Sekarang ini akan berhasil
        Schema::defaultStringLength(191);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        // Cap default string column length so utf8mb4 indexes stay within the
        // 1000-byte key limit on older MySQL/MariaDB (255*4 > 1000 otherwise).
        Schema::defaultStringLength(191);
    }
}

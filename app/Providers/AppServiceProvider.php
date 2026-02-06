<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifyCsrfToken;

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
        BaseVerifyCsrfToken::except([
            '/inventory/in',
            '/inventory/move',
            '/inventory/move/bulk',
            '/inventory/bulk-in',
            '/inventory/undo',
            '/inventory/logs/export',
            '/inventory/stock/export',
            '/locations/*',
            '/dashboard',
            '/login',
            '/auth/login',
        ]);
    }
}

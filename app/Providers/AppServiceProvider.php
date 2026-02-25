<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Baris di bawah ini sangat penting untuk memperbaiki error "not found" tadi
use Illuminate\Pagination\Paginator;

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
        // Baris ini yang akan merapikan tampilan pagination Anda
        Paginator::useBootstrapFive();
    }
}

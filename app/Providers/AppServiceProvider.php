<?php

namespace App\Providers;

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
        \App\Models\TransferInventaris::observe(\App\Observers\TransferInventarisObserver::class);
        \App\Models\BarangRusak::observe(\App\Observers\BarangRusakObserver::class);
    }
}

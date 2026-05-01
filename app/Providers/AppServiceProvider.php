<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. Tambahkan ini

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
        if (config('app.env') === 'production') {
        config(['view.compiled' => '/tmp/storage/framework/views']);
        }
        // 2. Tambahkan logika ini: Jika url mengandung kata 'ngrok', paksa pakai HTTPS
        if (str_contains(request()->getHost(), 'ngrok') || str_contains(request()->getHost(), 'infinityfree')) {
            URL::forceScheme('https');
        }
    }
}
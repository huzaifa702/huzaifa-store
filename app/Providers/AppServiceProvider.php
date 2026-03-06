<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS when not running locally
        if (!app()->environment('local')) {
            URL::forceScheme('https');
        }

        // Force cookie session driver to prevent 419 CSRF errors
        // Cookie sessions are most reliable on Railway/cloud platforms
        config(['session.driver' => 'cookie']);
    }
}

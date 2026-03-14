<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        if (!app()->runningUnitTests()) {
            View::composer('layouts.app', function ($view) {
                try {
                    $view->with('navCategories', Category::where('is_active', true)
                        ->orderBy('sort_order')->get());
                } catch (\Throwable $e) {
                    $view->with('navCategories', collect());
                }
            });
        }
    }
}

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
        \App\Models\User::observe(\App\Observers\UserObserver::class);

        if (str_contains(config('app.url'), 'ngrok') || str_contains(config('app.url'), 'expose') || str_contains(config('app.url'), 'cloudflare')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}

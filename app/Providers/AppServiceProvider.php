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
         $this->app->bind(
            \App\Contracts\Services\Auth\FirebaseAuthServiceInterface::class,
            \App\Services\Auth\FirebaseAuthService::class
        );

        $this->app->bind(
            \App\Contracts\Services\Auth\AuthServiceInterface::class,
            \App\Services\Auth\AuthService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

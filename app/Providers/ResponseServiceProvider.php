<?php

namespace App\Providers;

use App\Contracts\Response\ApiResponseInterface;
use App\Contracts\Response\ResponseInterface;
use App\Services\Response\ApiResponseService;
use App\Services\Response\JsonResponseService;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ResponseInterface::class, JsonResponseService::class);
        $this->app->bind(ApiResponseInterface::class, function ($app) {
            return new ApiResponseService($app->make(ResponseInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

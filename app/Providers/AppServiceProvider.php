<?php

namespace App\Providers;

use Illuminate\Http\Response;
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
        Response::macro('toJsonResponse', function ($data = [], $status = 200, array $headers = [], $options = 0) {
            return response()->json($data, $status, $headers, $options);
        });

    }
}

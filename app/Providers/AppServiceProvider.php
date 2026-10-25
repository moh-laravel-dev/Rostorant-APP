<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request; // For working with database
use Illuminate\Http\Resources\Json\JsonResource; // Base service provider class
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
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
        JsonResource::withoutWrapping();
        Paginator::useBootstrapFive();
        // Check if the database connection is available when the app boots up
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            // If the database connection fails, display an error message and stop the execution
            echo "❌ [DATABASE ERROR] MySQL is not running or .env config is invalid.\n";
            echo 'Reason: '.$e->getMessage()."\n";
            exit(1); // Stop the execution of the app
        }

    }
}

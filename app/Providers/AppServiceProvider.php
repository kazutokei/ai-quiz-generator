<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
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
        // Define rate limiter to protect the AI API (5 requests per hour per IP)
        RateLimiter::for('generate-quiz', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // Ensure the pdfs upload directory exists
        $pdfsDir = storage_path('app/pdfs');
        if (! is_dir($pdfsDir)) {
            mkdir($pdfsDir, 0755, true);
        }
    }
}

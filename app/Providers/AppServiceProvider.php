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
        // Define rate limiter to protect the AI API (configurable via env)
        RateLimiter::for('generate-quiz', function (Request $request) {
            $limit = config('services.groq.rate_limit_per_hour', 60);
            return $limit > 0
                ? Limit::perHour($limit)->by($request->ip())
                : Limit::none();
        });

        // Ensure the pdfs upload directory exists
        $pdfsDir = storage_path('app/pdfs');
        if (! is_dir($pdfsDir)) {
            mkdir($pdfsDir, 0755, true);
        }
    }
}

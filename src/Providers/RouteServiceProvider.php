<?php

namespace OtpLogin\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        RateLimiter::for('otp', function ($request) {
            return Limit::perHour(5);
        });

        RateLimiter::for('otp-verify', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });

    }
}

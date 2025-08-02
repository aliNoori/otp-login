<?php

namespace OtpLogin\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

/**
 * Class RouteServiceProvider
 *
 * Registers custom rate limiters for OTP-related endpoints.
 * Helps prevent abuse and brute-force attacks during OTP requests and verification.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * Rate limiter for sending OTP codes.
         * Limits to 5 requests per hour per user (based on phone or session).
         */
        RateLimiter::for('otp', function ($request) {
            return Limit::perHour(5);
        });

        /**
         * Rate limiter for verifying OTP codes.
         * Limits to 5 attempts per minute per IP address.
         * Helps mitigate brute-force attacks.
         */
        RateLimiter::for('otp-verify', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}

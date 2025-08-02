<?php

use Illuminate\Support\Facades\Route;
use OtpLogin\Http\Controllers\SendOtpController;
use OtpLogin\Http\Controllers\VerifyOtpController;

/**
 * OTP Authentication Routes
 *
 * These routes handle sending and verifying one-time passwords (OTP)
 * for phone-based authentication. Designed for API usage.
 */
Route::middleware('api')
    ->prefix('api/otp')
    ->group(function () {

        /**
         * Send OTP Code
         *
         * Endpoint: POST /api/otp/send
         * Accepts a phone number and triggers OTP generation and SMS dispatch.
         * Optional: Add custom throttling middleware to prevent abuse.
         */
        Route::post('send', [SendOtpController::class, 'send'])
            ->name('otp.send')
            ->middleware('throttle:otp'); // Uncomment to enable custom rate limiting

        /**
         * Verify OTP Code
         *
         * Endpoint: POST /api/otp/verify
         * Accepts phone number and OTP code, verifies them, and logs in the user.
         * Throttling middleware is enabled to protect against brute-force attempts.
         */
        Route::post('verify', [VerifyOtpController::class, 'verify'])
            ->name('otp.verify')
            ->middleware('throttle:otp-verify');
    });

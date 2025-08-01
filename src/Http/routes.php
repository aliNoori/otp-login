<?php

use Illuminate\Support\Facades\Route;
use OtpLogin\Http\Controllers\SendOtpController;
use OtpLogin\Http\Controllers\VerifyOtpController;

Route::middleware('api')

    ->prefix('api/otp')

    ->group(function () {

        Route::post('send', [SendOtpController::class, 'send'])
            ->name('otp.send')/*->middleware('throttle:otp');*/;

        Route::post('verify', [VerifyOtpController::class, 'verify'])
            ->name('otp.verify')->middleware('throttle:otp-verify');
    });





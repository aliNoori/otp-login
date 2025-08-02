<?php

use OtpLogin\Handlers\JwtLoginHandler;
use OtpLogin\Handlers\SanctumLoginHandler;
use OtpLogin\Handlers\SessionLoginHandler;
use OtpLogin\Services\KavenegarSmsSender;
use OtpLogin\Services\ModirPayamakSmsSender;

return [

    /*
    |--------------------------------------------------------------------------
    | Login Handler Configuration
    |--------------------------------------------------------------------------
    |
    | Determines which login handler should be used for OTP authentication.
    | Supported options: 'jwt', 'sanctum', 'session'.
    | You can override the default via the OTP_LOGIN_HANDLER environment variable.
    |
    */
    'handler' => [
        'key' => env('OTP_LOGIN_HANDLER', 'jwt'), // Default: jwt
        'map' => [
            'jwt' => JwtLoginHandler::class,
            'sanctum' => SanctumLoginHandler::class,
            'session' => SessionLoginHandler::class,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Driver Configuration
    |--------------------------------------------------------------------------
    |
    | Specifies which SMS provider to use for sending OTP codes.
    | You can switch between drivers using the OTP_SMS_DRIVER environment variable.
    |
    */
    'driver' => env('OTP_SMS_DRIVER', 'modirpayamak'),

    'drivers' => [
        'modirpayamak' => [
            'class' => ModirPayamakSmsSender::class,
            'key' => env('MODIR_API_KEY'), // API key for ModirPayamak
            'sender' => env('MODIR_SENDER', '10004346') // Default sender number
        ],
        'kavenegar' => [
            'class' => KavenegarSmsSender::class,
            'key' => env('KAVENEGAR_API_KEY'), // API key for Kavenegar
            'sender' => env('KAVENEGAR_SENDER', '10004346') // Default sender number
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Code Lifetime
    |--------------------------------------------------------------------------
    |
    | Defines how long the OTP code remains valid (in seconds).
    | After expiration, the code cannot be used for authentication.
    |
    */
    'code_lifetime' => 120, // 2 minutes

    /*
    |--------------------------------------------------------------------------
    | Model Bindings
    |--------------------------------------------------------------------------
    |
    | Allows customization of the model used to store OTP codes.
    | You can override the default model if needed.
    |
    */
    'models' => [
        'otp' => \OtpLogin\Models\OtpCode::class,
    ],
];

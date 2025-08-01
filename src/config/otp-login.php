<?php
use OtpLogin\Handlers\JwtLoginHandler;
use OtpLogin\Handlers\SanctumLoginHandler;
use OtpLogin\Handlers\SessionLoginHandler;
use OtpLogin\Services\KavenegarSmsSender;
use OtpLogin\Services\ModirPayamakSmsSender;

return [

    /*
    |--------------------------------------------------------------------------
    | Login Handler
    |--------------------------------------------------------------------------
    | This defines which login handler will be used (jwt, sanctum, session)
    */

    'handler' => [
        'key' => env('OTP_LOGIN_HANDLER', 'jwt'), // jwt | sanctum | session
        'map' => [
            'jwt' => JwtLoginHandler::class,
            'sanctum' => SanctumLoginHandler::class,
            'session' => SessionLoginHandler::class,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Driver
    |--------------------------------------------------------------------------
    */
    'driver' => env('OTP_SMS_DRIVER', 'modirpayamak'),

    'drivers' => [
        'modirpayamak' => [
            'class'=> ModirPayamakSmsSender::class,
            'key' => env('MODIR_API_KEY'),
            'sender' => env('MODIR_SENDER', '10004346')
        ],
        'kavenegar' => [
            'class' => KavenegarSmsSender::class,
            'key' => env('KAVENEGAR_API_KEY'),
            'sender' => env('KAVENEGAR_SENDER', '10004346')
        ],
    ],
    'code_lifetime' => 120, // seconds
];

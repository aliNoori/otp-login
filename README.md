
# OtpLogin

**OtpLogin** is a Laravel package for OTP-based authentication, supporting multiple login handlers and SMS drivers, making it highly flexible and customizable.

---

## 📦 Installation

Install the package via Composer:

```bash
composer require amedev/otp-login
```

Publish package assets:

```bash
php artisan vendor:publish --tag=otp-login-config
php artisan vendor:publish --tag=otp-login-migrations
php artisan vendor:publish --tag=otp-login-models
php artisan vendor:publish --tag=otp-login-translations
```

Run migrations:

```bash
php artisan migrate
```

---

## ⚙️ Configuration

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Login Handler Configuration
    |--------------------------------------------------------------------------
    | Determines which login handler is active.
    | Supported options:
    |  - 'jwt'     : Uses JWT for authentication.
    |  - 'sanctum' : Uses Laravel Sanctum.
    |  - 'session' : Uses standard session-based login.
    | Override default with env variable `OTP_LOGIN_HANDLER`.
    */
    'handler' => [
        'key' => env('OTP_LOGIN_HANDLER', 'jwt'),
        'map' => [
            'jwt' => \OtpLogin\Handlers\JwtLoginHandler::class,
            'sanctum' => \OtpLogin\Handlers\SanctumLoginHandler::class,
            'session' => \OtpLogin\Handlers\SessionLoginHandler::class,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Driver Configuration
    |--------------------------------------------------------------------------
    | Defines which SMS gateway driver to use for sending OTP codes.
    | Switch drivers with `OTP_SMS_DRIVER` environment variable.
    */
    'driver' => env('OTP_SMS_DRIVER', 'modirpayamak'),

    'drivers' => [
        'modirpayamak' => [
            'class' => \OtpLogin\Services\ModirPayamakSmsSender::class,
            'key' => env('SMS_PATTERN_CODE'),      // ModirPayamak API pattern code
            'sender' => env('SMS_SENDER', '10004346'), // Default sender number
            'username' => env('SMS_USERNAME'),
            'password' => env('SMS_PASSWORD'),
        ],
        'kavenegar' => [
            'class' => \OtpLogin\Services\KavenegarSmsSender::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Code Lifetime
    |--------------------------------------------------------------------------
    | Duration in seconds for which the OTP code remains valid.
    | Default is 120 seconds (2 minutes).
    */
    'code_lifetime' => 120,

    /*
    |--------------------------------------------------------------------------
    | Model Bindings
    |--------------------------------------------------------------------------
    | Allows overriding the default OTP model.
    | Useful if you want to extend or customize the OTP code model.
    */
    'models' => [
        'otp' => \OtpLogin\Models\OtpCode::class,
    ],

];
```

---

## 🧪 API Usage

### Send OTP

```http
POST /api/otp/send
Content-Type: application/json

{
  "phone": "09123456789",
  "country": "+98"
}
```

Response:

```json
{
  "success": true,
  "status": 200,
  "message": "کد تأیید ارسال شد.",
  "data": ""
}
```

---

### Verify OTP

```http
POST /api/otp/verify
Content-Type: application/json

{
  "phone": "09123456789",
  "code": "1234"
}
```

Response:

```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

---

## 🌐 Localization

Language files are published to:

```
resources/lang/vendor/otp-login
```

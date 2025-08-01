composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret

'guards' => [
        'api' => [
             'driver' => 'jwt',
             'provider' => 'users',
        ],
],

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
public function getJWTIdentifier()
{
return $this->getKey();
}

    public function getJWTCustomClaims()
    {
        return [];
    }
}




composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

packages/
└── OtpLogin/
   └── src/
      ├── config/
      │   └── otp-login.php
      ├── Contracts/
      │   └── SmsSenderInterface.php
      ├── Events/
      │   └── OtpRequested.php
      ├── Http/
      │   ├── Controllers/
      │   │   ├── SendOtpController.php
      │   │   └── VerifyOtpController.php
      │   ├── Middleware/
      │   └── Requests/
      │       ├── SendOtpRequest.php
      │       └── VerifyOtpRequest.php
      │   └── routes.php
      ├── Listeners/
      │   └── SendOtpListener.php
      ├── Models/
      │   └── OtpCode.php
      ├── Providers/
      │   ├── RouteServiceProvider.php
      │   └── EventServiceProvider.php
      ├── Services/
      │   ├── OtpCodeService.php
      │   └── OtpPolicyService.php
      ├── Traits/
      │   └── HasApiResponses.php
      ├── resources/
      │   └── lang/
      │       └── fa/
      │           └── sms.php
      └── OtpLoginServiceProvider.php

composer require amedev/otp-login:dev-main

php artisan migrate --path=packages/OtpLogin/src/Database/Migrations


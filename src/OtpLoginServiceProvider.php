<?php

namespace OtpLogin;

use Illuminate\Support\ServiceProvider;
use OtpLogin\Contracts\SmsSenderInterface;
use OtpLogin\Providers\EventServiceProvider;
use OtpLogin\Providers\RouteServiceProvider;
use OtpLogin\Services\OtpCodeService;

class OtpLoginServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');

        $this->mergeConfigFrom(__DIR__.'/config/otp-login.php', 'otp-login');

        $this->publishes([
            __DIR__.'/config/otp-login.php' => config_path('otp-login.php'),
        ], 'otp-login-config');

        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'otp-login');

        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/vendor/otp-login'),
        ], 'otp-login-translations');

        // مایگریشن‌ها رو لود کن
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        $this->publishes([
            __DIR__ . '/Database/Migrations' => database_path('migrations'),
        ], 'otp-login-migrations');


        $this->publishes([
            __DIR__ . '/publishable/Models' => app_path('Models/OtpLogin'),
        ], 'otp-login-models');


        $this->app->register(EventServiceProvider::class);

        $this->app->register(RouteServiceProvider::class);
    }

    public function register(): void
    {


        $this->app->bind(OtpCodeService::class);

        $this->app->bind(SmsSenderInterface::class, function () {
            $driver = config('otp-login.driver');
            $driverConfig = config("otp-login.drivers.$driver");
            $class = $driverConfig['class'] ?? null;

            if (!is_string($class) || !class_exists($class)) {
                throw new \RuntimeException("Invalid SMS driver class: $class");
            }

            return new $class();
        });
    }
}

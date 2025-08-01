<?php

namespace OtpLogin\Providers;

use Illuminate\Support\ServiceProvider;
use OtpLogin\Contracts\SmsSenderInterface;
use OtpLogin\Services\OtpCodeService;
use src\Console\Commands\FixModelNamespace;

class OtpLoginServiceProvider extends ServiceProvider
{
    private string $basePath;

    public function boot(): void
    {
        $this->basePath = dirname(__DIR__);

        $this->loadRoutesFrom($this->basePath . '/Http/routes.php');

        $this->mergeConfigFrom($this->basePath . '/config/otp-login.php', 'otp-login');

        $this->publishes([
            $this->basePath . '/config/otp-login.php' => config_path('otp-login.php'),
        ], 'otp-login-config');

        $this->loadTranslationsFrom($this->basePath . '/resources/lang', 'otp-login');

        $this->publishes([
            $this->basePath . '/resources/lang' => resource_path('lang/vendor/otp-login'),
        ], 'otp-login-translations');

        $this->loadMigrationsFrom($this->basePath . '/Database/Migrations');

        $this->publishes([
            $this->basePath . '/Database/Migrations' => database_path('migrations'),
        ], 'otp-login-migrations');

        $this->publishes([
            $this->basePath . '/Models' => app_path('Models'),
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

        $this->commands([
            FixModelNamespace::class,
        ]);
    }
}

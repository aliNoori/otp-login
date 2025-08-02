<?php

namespace OtpLogin\Providers;

use Illuminate\Support\ServiceProvider;
use OtpLogin\Contracts\SmsSenderInterface;
use OtpLogin\Services\OtpCodeService;
use OtpLogin\Console\Commands\FixModelNamespace;

/**
 * Class OtpLoginServiceProvider
 *
 * Main service provider for the OTP Login package.
 * Handles registration of routes, config, migrations, translations,
 * bindings, and console commands.
 */
class OtpLoginServiceProvider extends ServiceProvider
{
    /**
     * Base path of the package.
     *
     * @var string
     */
    private string $basePath;

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->basePath = dirname(__DIR__);

        // Load package routes
        $this->loadRoutesFrom($this->basePath . '/Http/routes.php');

        // Merge default config
        $this->mergeConfigFrom($this->basePath . '/config/otp-login.php', 'otp-login');

        // Publish config file
        $this->publishes([
            $this->basePath . '/config/otp-login.php' => config_path('otp-login.php'),
        ], 'otp-login-config');

        // Load and publish translations
        $this->loadTranslationsFrom($this->basePath . '/resources/lang', 'otp-login');

        $this->publishes([
            $this->basePath . '/resources/lang' => resource_path('lang/vendor/otp-login'),
        ], 'otp-login-translations');

        // Load and publish migrations
        $this->loadMigrationsFrom($this->basePath . '/Database/Migrations');

        $this->publishes([
            $this->basePath . '/Database/Migrations' => database_path('migrations'),
        ], 'otp-login-migrations');

        // Optionally publish model stub for customization
        $this->publishes([
            $this->basePath . '/Models' => app_path('Models'),
        ], 'otp-login-models');

        // Register internal service providers
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind OTP code service
        $this->app->bind(OtpCodeService::class);

        // Dynamically bind the SMS sender based on config
        $this->app->bind(SmsSenderInterface::class, function () {
            $driver = config('otp-login.driver');
            $driverConfig = config("otp-login.drivers.$driver");
            $class = $driverConfig['class'] ?? null;

            if (!is_string($class) || !class_exists($class)) {
                throw new \RuntimeException("Invalid SMS driver class: $class");
            }

            return new $class();
        });

        // Register artisan commands
        $this->commands([
            FixModelNamespace::class,
        ]);
    }
}

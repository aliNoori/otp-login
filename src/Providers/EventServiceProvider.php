<?php

namespace OtpLogin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use OtpLogin\Events\OtpRequested;
use OtpLogin\Listeners\SendOtpListener;

/**
 * Class EventServiceProvider
 *
 * Registers event-to-listener mappings for the OTP login package.
 * Ensures that when an OTP is requested, the corresponding SMS job is dispatched.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the package.
     *
     * @var array
     */
    protected $listen = [
        // When an OTP is requested, send it via SMS
        OtpRequested::class => [
            SendOtpListener::class,
        ],
    ];
}

<?php

namespace OtpLogin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use OtpLogin\Events\OtpRequested;
use OtpLogin\Listeners\SendOtpListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OtpRequested::class => [
            SendOtpListener::class,
        ],
    ];
}

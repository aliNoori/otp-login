<?php
namespace OtpLogin\Listeners;

use OtpLogin\Events\OtpRequested;
use OtpLogin\Jobs\SendOtpJob;

class SendOtpListener
{
    public function handle(OtpRequested $event): void
    {
        dispatch(new SendOtpJob(
            phone: $event->otp->phone,
            code: $event->otp->code
        ));
    }
}

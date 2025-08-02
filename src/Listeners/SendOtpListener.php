<?php

namespace OtpLogin\Listeners;

use OtpLogin\Events\OtpRequested;
use OtpLogin\Jobs\SendOtpJob;

/**
 * Listener: SendOtpListener
 *
 * Listens for the OtpRequested event and dispatches a queued job
 * to send the OTP code via SMS.
 */
class SendOtpListener
{
    /**
     * Handle the event.
     *
     * @param OtpRequested $event The event containing the OTP model.
     * @return void
     */
    public function handle(OtpRequested $event): void
    {
        // Dispatch the job to send the OTP code via SMS
        dispatch(new SendOtpJob(
            phone: $event->otp->phone,
            code: $event->otp->code
        ));
    }
}

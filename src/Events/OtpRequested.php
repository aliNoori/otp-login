<?php

namespace OtpLogin\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Database\Eloquent\Model;

/**
 * Event: OtpRequested
 *
 * This event is dispatched when a new OTP code is generated and stored.
 * It can be used to trigger listeners for logging, notifications, analytics, etc.
 */
class OtpRequested
{
    use Dispatchable;

    /**
     * The OTP model instance containing phone number, code, and expiration.
     *
     * @return void
     */
    public function __construct(public Model $otp) {}
}

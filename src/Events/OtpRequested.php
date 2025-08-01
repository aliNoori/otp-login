<?php
namespace OtpLogin\Events;

use Illuminate\Foundation\Events\Dispatchable;
use OtpLogin\Models\OtpCode;

class OtpRequested
{
    use Dispatchable;

    public function __construct(public OtpCode $otp) {}
}

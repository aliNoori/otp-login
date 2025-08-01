<?php
namespace OtpLogin\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Database\Eloquent\Model;

class OtpRequested
{
    use Dispatchable;

    public function __construct(public Model $otp) {}
}

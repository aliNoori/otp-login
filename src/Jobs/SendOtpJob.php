<?php

namespace OtpLogin\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use OtpLogin\Contracts\SmsSenderInterface;

class SendOtpJob implements ShouldQueue
{
    public function __construct(
        public string $phone,
        public string $code
    ) {}

    public function handle(SmsSenderInterface $sms): void
    {
        //$sms->send($this->phone, "کد ورود شما: {$this->code}");
        $sms->send($this->phone, $this->code);
    }
}

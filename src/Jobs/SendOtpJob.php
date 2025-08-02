<?php

namespace OtpLogin\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use OtpLogin\Contracts\SmsSenderInterface;

/**
 * Job: SendOtpJob
 *
 * Dispatches an SMS containing the OTP code to the user's phone number.
 * This job is queued to ensure non-blocking delivery and scalability.
 */
class SendOtpJob implements ShouldQueue
{
    /**
     * The recipient's phone number.
     *
     * @var string
     */
    public string $phone;

    /**
     * The OTP code to be sent.
     *
     * @var string
     */
    public string $code;

    /**
     * Create a new job instance.
     *
     * @param string $phone The recipient's phone number.
     * @param string $code The OTP code to be sent.
     */
    public function __construct(string $phone, string $code)
    {
        $this->phone = $phone;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @param SmsSenderInterface $sms The SMS sender implementation.
     * @return void
     */
    public function handle(SmsSenderInterface $sms): void
    {
        // Send the OTP code via SMS
        $sms->send($this->phone, $this->code);
        // You can customize the message format here if needed:
        // $sms->send($this->phone, "Your login code is: {$this->code}");
    }
}

<?php

namespace OtpLogin\Services;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use OtpLogin\Contracts\SmsSenderInterface;

/**
 * Class KavenegarSmsSender
 *
 * Sends OTP messages using the Kavenegar SMS gateway.
 * Implements the SmsSenderInterface for driver-based dispatching.
 */
class KavenegarSmsSender implements SmsSenderInterface
{
    /**
     * Kavenegar API key.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * Sender line number (default: 10004346).
     *
     * @var string
     */
    protected string $sender;

    /**
     * Initialize the sender with config values.
     */
    public function __construct()
    {
        $this->apiKey = config('otp-login.drivers.kavenegar.key');
        $this->sender = config('otp-login.drivers.kavenegar.sender', '10004346');
    }

    /**
     * Send an SMS message via Kavenegar.
     *
     * @param string $phoneNumber The recipient's phone number.
     * @param string $message The message content to be sent.
     * @return bool True if the message was successfully sent.
     *
     * @throws Exception If the request fails or the gateway returns an error.
     */
    public function send(string $phoneNumber, string $message): bool
    {

        $url = "https://api.kavenegar.com/v1/{$this->apiKey}/sms/send.json";



        try {
            $response = Http::get($url, [
                'receptor' => $phoneNumber,
                'message'  => $message,
                'sender'   => $this->sender,
            ]);

            if ($response->successful()) {
                logger("Kavenegar → Sent to {$phoneNumber}: {$message}");
                return true;
            }

            // Handle known error codes
            switch ($response->status()) {
                case 400:
                    throw new Exception(__('sms.invalid_phone'));
                case 403:
                    throw new Exception(__('sms.sms_not_sent'));
                case 429:
                    logger("Too many requests. Rate limit exceeded.");
                    throw new Exception(__('sms.max_sms_attempts'));
                case 500:
                    throw new Exception(__('sms.gateway_unavailable'));
                default:
                    throw new Exception(__('sms.sms_not_sent'));
            }

        } catch (Exception $e) {
            // Re-throw with original message for upstream handling
            throw new Exception($e->getMessage());
        }
    }
}

<?php

namespace OtpLogin\Services;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use OtpLogin\Contracts\SmsSenderInterface;

/**
 * Class ModirPayamakSmsSender
 *
 * Sends OTP messages using the ModirPayamak (IPPanel) SMS gateway.
 * Implements the SmsSenderInterface for driver-based dispatching.
 */
class ModirPayamakSmsSender implements SmsSenderInterface
{
    /**
     * Send an SMS message via ModirPayamak (IPPanel).
     *
     * @param string $phoneNumber The recipient's phone number.
     * @param string $message The OTP code or message content.
     * @return bool True if the message was successfully sent.
     *
     * @throws Exception If the request fails or the gateway returns an error.
     */
    public function send(string $phoneNumber, string $message): bool
    {
        // API endpoint for pattern-based SMS
        $url = 'https://api2.ippanel.com/api/v1/sms/pattern/normal/send';

        $driverConfig = Config::get('otp-login.drivers');

        // Prepare payload with dynamic pattern variables
        $payload = [
            'code'     => $driverConfig['key'], // Pattern code defined in IPPanel
            'sender'   => $driverConfig['sender'],       // Sender line number
            'recipient'=> $phoneNumber,            // Destination phone number
            'variable' => [
                'verification-code' => $message    // Inject OTP code into pattern
            ]
        ];

        try {
            // Send POST request with Basic Auth credentials
            $response = Http::withBasicAuth(
                $driverConfig['username'],
                $driverConfig['password'],
            )
                ->asJson()
                ->post($url, $payload);

            // Handle successful response
            if ($response->successful()) {
                logger("ModirPayamak → Sent to {$phoneNumber}: {$message}");
                return true;
            }

            // Handle known error codes
            throw match ($response->status()) {
                400 => new Exception(__('sms.invalid_phone')),
                429 => new Exception(__('sms.max_sms_attempts')),
                500 => new Exception(__('sms.gateway_unavailable')),
                default => new Exception(__('sms.sms_not_sent')),
            };

        } catch (Exception $e) {
            // Log and rethrow for upstream handling
            logger("ModirPayamak Exception → " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}

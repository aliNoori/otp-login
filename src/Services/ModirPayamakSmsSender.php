<?php
namespace OtpLogin\Services;

use Exception;
use OtpLogin\Contracts\SmsSenderInterface;
use Illuminate\Support\Facades\Http;

class ModirPayamakSmsSender implements SmsSenderInterface
{
    /**
     * Send SMS using the specified API
     *
     * @param string $phoneNumber
     * @param string $message The message to send
     * @return bool True if the SMS was sent successfully, false otherwise
     * @throws Exception
     */
    public function send(string $phoneNumber, string $message): bool
    {
        // API endpoint
        $url = 'https://api2.ippanel.com/api/v1/sms/pattern/normal/send';

        // Payload for the API request
        $payload = [
            'code' => env('SMS_PATTERN_CODE', 'hdsxp8rpsuerefo'), // Pattern code
            'sender' => env('SMS_SENDER', '+983000505'),          // Sender number
            'recipient' => $phoneNumber,                                // Destination phone number
            'variable' => [                                       // Variables for pattern
                'verification-code' => $message                  // Dynamic data within pattern
            ]
        ];
        try {
            // Send POST request to SMS service with Basic Auth
            $response = Http::withBasicAuth(env('SMS_USERNAME', '9142766601'), env('SMS_PASSWORD', 'Mh@36463646'))
                ->asJson()
                ->post($url, $payload);


            if ($response->successful()) {
                logger("SMS sent successfully to $phoneNumber: $message");
                return true;
            }
            // بررسی وضعیت‌های مختلف خطا
            $statusCode = $response->status();
            throw match ($statusCode) {
                400 => new \Exception(__('sms.invalid_phone')),
                429 => new \Exception(__('sms.max_sms_attempts')),
                500 => new \Exception(__('sms.gateway_unavailable')),
                default => new \Exception(__('sms.sms_not_sent')),
            };
        } catch (\Exception $e) {
            logger("Exception while sending SMS: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}

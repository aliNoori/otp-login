<?php
namespace OtpLogin\Services;

use Exception;
use OtpLogin\Contracts\SmsSenderInterface;
use Illuminate\Support\Facades\Http;

class KavenegarSmsSender implements SmsSenderInterface
{
    protected string $apiKey;
    protected string $sender;

    public function __construct()
    {
        $this->apiKey = config('otp-login.drivers.kavenegar.key'); // از فایل config/services.php
        $this->sender = config('otp-login.drivers.kavenegar.sender', '10004346'); // پیش‌فرض خط فرستنده
    }

    /**
     * @throws Exception
     */
    public function send(string $phoneNumber, string $message): bool
    {
        $url = "https://api.kavenegar.com/v1/{$this->apiKey}/sms/send.json";

        try {
            $response = Http::get($url, [
                'receptor' => $phoneNumber,
                'message' => $message,
                'sender' => $this->sender,
            ]);


            if ($response->successful()) {
                logger("Kavenegar → Sent to $phoneNumber: $message");
                return true;
            }
            $statusCode = $response->status();
            switch ($statusCode) {
                case 400:
                    //logger("Invalid phone number or request format.");
                    throw new Exception(__('sms.invalid_phone'));
                case 403:
                    //logger("SMS API access denied.");
                    throw new Exception(__('sms.sms_not_sent'));
                case 429:
                    logger("Too many requests. Rate limit exceeded.");
                    throw new Exception(__('sms.max_sms_attempts'));
                case 500:
                    //logger("SMS service unavailable.");
                    throw new Exception(__('sms.gateway_unavailable'));
                default:
                    //logger("Unexpected error. Response: " . $response->body());
                    throw new Exception(__('sms.sms_not_sent'));
            }
        } catch (Exception $e) {

            //logger("Exception while sending SMS: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}

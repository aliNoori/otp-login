<?php

namespace OtpLogin\Services;

use Illuminate\Validation\ValidationException;
use OtpLogin\Traits\HasApiResponses;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * Class OtpPolicyService
 *
 * Enforces rate-limiting and request policies for OTP generation.
 * Prevents abuse by checking for recent codes and excessive requests.
 */
class OtpPolicyService
{
    use HasApiResponses;

    /**
     * Validate whether an OTP can be sent to the given phone number.
     *
     * @param string $phone The recipient's phone number.
     * @throws TooManyRequestsHttpException If policy limits are exceeded.
     */
    public function validateRequest(string $phone): void
    {
        $model = config('otp-login.models.otp');

        // Prevent sending a new code if one is still valid
        if ($model::where('phone', $phone)
            ->where('expires_at', '>', now())
            ->exists()) {
            throw new TooManyRequestsHttpException(null, __('otp-login::sms.sms_recent_code'));
        }

        // Limit to 10 OTP requests per day per phone number
        if ($model::where('phone', $phone)
                ->where('created_at', '>=', now()->subDay())
                ->count() > 10) {
            throw new TooManyRequestsHttpException(null, __('otp-login::sms.max_sms_attempts'));
        }
    }
}

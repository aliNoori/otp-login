<?php

namespace OtpLogin\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OtpLogin\Traits\HasApiResponses;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Tymon\JWTAuth\Blacklist;

//use App\Models\Blacklist;

class OtpPolicyService
{
    use HasApiResponses;

    public function validateRequest(string $phone): void
    {
        $model = app(config('otp-login.models.otp'));

        if ($model::where('phone', $phone)
            ->where('expires_at', '>', now())
            ->exists()) {
            throw new TooManyRequestsHttpException(null, __('otp-login::sms.sms_recent_code'));
        }

        if ($model::where('phone', $phone)
                ->where('created_at', '>=', now()->subDay())
                ->count() > 10) {
            throw new TooManyRequestsHttpException(null, __('otp-login::sms.max_sms_attempts'));
        }
    }
}

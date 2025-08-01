<?php

namespace OtpLogin\Services;

use OtpLogin\Models\OtpCode;

class OtpVerificationService
{
    public function verify(string $phone, string $code): bool
    {
        $model = config('otp-login.models.otp');

        $otp = $model::where('phone', $phone)
            ->where('code', $code)
            ->latest()
            ->first();

        if (! $otp || $otp->isExpired()) {
            return false;
        }

        $otp->delete();

        return true;
    }
}

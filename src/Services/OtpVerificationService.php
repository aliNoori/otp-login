<?php

namespace OtpLogin\Services;

use OtpLogin\Models\OtpCode;

class OtpVerificationService
{
    public function verify(string $phone, string $code): bool
    {
        $otp = OtpCode::where('phone', $phone)
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

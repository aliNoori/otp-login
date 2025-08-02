<?php

namespace OtpLogin\Services;

/**
 * Class OtpVerificationService
 *
 * Handles verification of OTP codes.
 * Validates the code against stored records and enforces expiration.
 */
class OtpVerificationService
{
    /**
     * Verify the OTP code for the given phone number.
     *
     * @param string $phone The phone number to verify.
     * @param string $code The OTP code submitted by the user.
     * @return bool True if the code is valid and not expired; false otherwise.
     */
    public function verify(string $phone, string $code): bool
    {
        $model = config('otp-login.models.otp');

        // Retrieve the most recent matching OTP code
        $otp = $model::where('phone', $phone)
            ->where('code', $code)
            ->latest()
            ->first();

        // Reject if not found or expired
        if (! $otp || $otp->isExpired()) {
            return false;
        }

        // Delete the OTP after successful verification (one-time use)
        $otp->delete();

        return true;
    }
}

<?php

namespace OtpLogin\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OtpCodeService
 *
 * Handles generation and persistence of OTP codes.
 * Responsible for creating new OTP records and managing expiration.
 */
class OtpCodeService
{
    /**
     * Generate a random 4-digit OTP code.
     *
     * @return string
     * @throws Exception If random_int fails.
     */
    public function generateCode(): string
    {
        // Generates a secure 4-digit code (e.g. 1234)
        return str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create and persist a new OTP code for the given phone number.
     *
     * @param string $phone The recipient's phone number.
     * @param int $ttlSeconds Time-to-live in seconds (default: 120).
     * @return Model The newly created OTP model instance.
     *
     * @throws Exception If model creation fails or config is invalid.
     */
    public function create(string $phone, int $ttlSeconds = 120): Model
    {
        // Resolve the model class from config
        $modelClass = config('otp-login.models.otp');

        // Create a new OTP record
        $model = $modelClass::create([
            'phone'       => $phone,
            'code'        => $this->generateCode(),
            'expires_at'  => now()->addSeconds($ttlSeconds),
        ]);

        // Validate model instance
        if (! $model instanceof Model) {
            throw new \RuntimeException('Invalid model class in config(otp-login.models.otp)');
        }

        return $model;
    }
}

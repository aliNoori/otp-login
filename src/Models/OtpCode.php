<?php

namespace OtpLogin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Model: OtpCode
 *
 * Represents a one-time password (OTP) record used for phone-based authentication.
 * Stores the phone number, OTP code, expiration timestamp, and audit timestamps.
 */
class OtpCode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'otp_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'code',
        'expires_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Resolve the model class from config, allowing customization.
     *
     * @return string
     */
    public static function customModel(): string
    {
        return config('otp-login.models.otp', self::class);
    }

    /**
     * Determine if the OTP code has expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}

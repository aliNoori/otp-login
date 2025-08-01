<?php

namespace OtpLogin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OtpCode extends Model
{
    protected $table = 'otp_codes';

    protected $fillable = [
        'phone',
        'code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public $timestamps = true;

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}

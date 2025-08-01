<?php

namespace OtpLogin\Services;

use Exception;
use OtpLogin\Models\OtpCode;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class OtpCodeService
{
    /**
     * @throws Exception
     */
    public function generateCode(): string
    {
        return str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * @throws Exception
     */
    public function create(string $phone, int $ttlSeconds = 120): OtpCode
    {
        $code = $this->generateCode();


        return OtpCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => Carbon::now()->addSeconds($ttlSeconds),
        ]);
    }
}

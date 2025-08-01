<?php

namespace OtpLogin\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


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
     * @return Model&Builder
     * @throws Exception
     */
    public function create(string $phone, int $ttlSeconds = 120): Model
    {
        $modelClass = config('otp-login.models.otp');

        /** @var Model $model */
        $model = $modelClass::create([
            'phone' => $phone,
            'code' => $this->generateCode(),
            'expires_at' => now()->addSeconds($ttlSeconds),
        ]);

        if (! $model instanceof Model) {
            throw new \RuntimeException('Invalid model class in config(otp-login.models.otp)');
        }

        return $model;
    }
}

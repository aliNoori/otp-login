<?php

namespace OtpLogin\Traits;

use Illuminate\Http\JsonResponse;

trait HasApiResponses
{
    public function ok($data = null, string $message = 'عملیات موفق بود', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status'  => $code,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public function fail(string $message = 'عملیات ناموفق بود', $errors = null, int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status'  => $code,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

}

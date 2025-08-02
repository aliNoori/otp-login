<?php

namespace OtpLogin\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Trait HasApiResponses
 *
 * Provides standardized JSON responses for API endpoints.
 * Ensures consistent structure for success and error messages.
 */
trait HasApiResponses
{
    /**
     * Return a successful JSON response.
     *
     * @param mixed|null $data Optional payload data.
     * @param string $message Success message (default: Persian "Operation was successful").
     * @param int $code HTTP status code (default: 200).
     * @return JsonResponse
     */
    public function ok(mixed $data = null, string $message = 'عملیات موفق بود', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status'  => $code,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Return a failed JSON response.
     *
     * @param string $message Error message (default: Persian "Operation failed").
     * @param mixed|null $errors Optional error details (e.g. validation errors).
     * @param int $code HTTP status code (default: 400).
     * @return JsonResponse
     */
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

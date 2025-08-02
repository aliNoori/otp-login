<?php

namespace OtpLogin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OtpLogin\Services\LoginHandlerResolver;
use OtpLogin\Services\OtpVerificationService;

/**
 * Class VerifyOtpController
 *
 * Handles OTP verification and user authentication.
 * Validates the OTP code, resolves the appropriate login handler,
 * and returns the authentication response.
 */
class VerifyOtpController extends Controller
{
    /**
     * Verify the OTP code and authenticate the user.
     *
     * @param Request $request The incoming request containing phone and OTP code.
     * @param OtpVerificationService $verifier Service for validating OTP codes.
     * @param LoginHandlerResolver $resolver Service for resolving the appropriate login handler.
     * @return JsonResponse The authentication response or error message.
     */
    public function verify(
        Request $request,
        OtpVerificationService $verifier,
        LoginHandlerResolver $resolver
    ): JsonResponse {
        // Validate incoming request data
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:4',
        ]);

        // Verify the OTP code
        if (! $verifier->verify($request->phone, $request->code)) {
            return response()->json([
                'message' => 'Invalid or expired OTP code.'
            ], 422);
        }

        // Resolve the configured login handler (JWT, Sanctum, Session)
        $handler = $resolver->resolve();

        // Delegate login to the resolved handler
        return response()->json($handler->login($request));
    }
}

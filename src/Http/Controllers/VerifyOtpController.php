<?php

namespace OtpLogin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OtpLogin\Services\LoginHandlerResolver;
use OtpLogin\Services\OtpVerificationService;

class VerifyOtpController extends Controller
{
    public function verify(Request $request, OtpVerificationService $verifier, LoginHandlerResolver $resolver): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:4',
        ]);

        if (! $verifier->verify($request->phone, $request->code)) {
            return response()->json(['message' => 'Invalid or expired OTP code.'], 422);
        }

        $handler = $resolver->resolve();

        return response()->json($handler->login($request));
    }
}

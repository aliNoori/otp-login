<?php

namespace OtpLogin\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use OtpLogin\Traits\HasApiResponses;
use OtpLogin\Events\OtpRequested;
use OtpLogin\Http\Requests\SendOtpRequest;
use OtpLogin\Services\OtpCodeService;
use OtpLogin\Services\OtpPolicyService;

/**
 * Class SendOtpController
 *
 * Handles OTP generation and dispatching via SMS.
 * This controller validates request policies, generates the OTP code,
 * and triggers the appropriate event for SMS delivery.
 */
class SendOtpController extends Controller
{
    use HasApiResponses;

    /**
     * Send an OTP code to the requested phone number.
     *
     * @param SendOtpRequest $request Validated request containing phone number.
     * @param OtpCodeService $otpCodeService Service for generating and storing OTP codes.
     * @param OtpPolicyService $otpPolicyService Service for enforcing rate limits and request policies.
     * @return JsonResponse A success response indicating the OTP was sent.
     *
     * @throws Exception If policy validation fails.
     */
    public function send(
        SendOtpRequest $request,
        OtpCodeService $otpCodeService,
        OtpPolicyService $otpPolicyService
    ): JsonResponse {
        // Normalize and retrieve the full phone number
        $fullPhone = $request->fullPhone();

        // Enforce OTP request policies (e.g. rate limiting, cooldown)
        $otpPolicyService->validateRequest($fullPhone);

        // Generate and persist the OTP code
        $otp = $otpCodeService->create($fullPhone);

        // Dispatch event to trigger SMS sending
        event(new OtpRequested($otp));

        // Return a standardized success response
        return $this->ok('', __('otp-login::sms.sms_sent'), 200);
    }
}

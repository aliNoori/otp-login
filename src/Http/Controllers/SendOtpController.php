<?php

namespace OtpLogin\Http\Controllers;

use OtpLogin\Traits\HasApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use OtpLogin\Events\OtpRequested;
use OtpLogin\Http\Requests\SendOtpRequest;
use OtpLogin\Services\OtpCodeService;
use OtpLogin\Services\OtpPolicyService;

class SendOtpController extends Controller
{
    use HasApiResponses;


    public function send(SendOtpRequest $request, OtpCodeService $otpCodeService, OtpPolicyService $otpPolicyService): JsonResponse
    {

        $fullPhone = $request->fullPhone();

        // بررسی سیاست‌ها
        $otpPolicyService->validateRequest($fullPhone);

        // ساخت OTP
        $otp = $otpCodeService->create($fullPhone);

        // ایونت برای ارسال پیامک
        event(new OtpRequested($otp));

        return $this->ok('',__('otp-login::sms.sms_sent'),200);
    }
}

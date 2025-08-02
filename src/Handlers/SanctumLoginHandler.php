<?php

namespace OtpLogin\Handlers;

use Illuminate\Http\Request;
use OtpLogin\Contracts\OtpLoginHandlerInterface;
use App\Models\User;

/**
 * Class SanctumLoginHandler
 *
 * Handles OTP-based login using Laravel Sanctum.
 * Assumes the user is identified by their phone number.
 */
class SanctumLoginHandler implements OtpLoginHandlerInterface
{
    /**
     * Perform login and issue a Sanctum token.
     *
     * @param Request $request The incoming request containing the phone number.
     * @return mixed An array containing the access token and user data.
     */
    public function login(Request $request): mixed
    {
        // Retrieve or create the user based on phone number
        $user = User::firstOrCreate([
            'phone' => $request->phone,
        ]);

        // Create a personal access token using Sanctum
        return [
            'token' => $user->createToken('otp-login')->plainTextToken,
            'user' => $user,
        ];
    }
}

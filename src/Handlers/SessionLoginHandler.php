<?php

namespace OtpLogin\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OtpLogin\Contracts\OtpLoginHandlerInterface;
use App\Models\User;

/**
 * Class SessionLoginHandler
 *
 * Handles OTP-based login using Laravel's session-based authentication.
 * Suitable for web-based applications where sessions are maintained.
 */
class SessionLoginHandler implements OtpLoginHandlerInterface
{
    /**
     * Perform login and initiate a session for the user.
     *
     * @param Request $request The incoming request containing the phone number.
     * @return mixed An array containing session details and user data.
     */
    public function login(Request $request): mixed
    {
        // Retrieve or create the user based on phone number
        $user = User::firstOrCreate([
            'phone' => $request->phone,
        ]);

        // Log the user in using Laravel's session guard
        Auth::login($user);

        // Return session and user information
        return [
            'message' => 'User logged in using session.',
            'session_id' => session()->getId(), // Optional: useful for debugging or tracking
            'user' => $user,
        ];
    }
}

<?php

namespace OtpLogin\Handlers;

use Illuminate\Http\Request;
use OtpLogin\Contracts\OtpLoginHandlerInterface;
use App\Models\User;

/**
 * Class JwtLoginHandler
 *
 * Handles OTP-based login using JWT authentication.
 * Assumes the user is identified by their phone number.
 */
class JwtLoginHandler implements OtpLoginHandlerInterface
{
    /**
     * Perform login and issue a JWT token.
     *
     * @param Request $request The incoming request containing the phone number.
     * @return array An array containing the access token and metadata.
     */
    public function login(Request $request): array
    {
        // Retrieve or create the user based on phone number
        $user = User::firstOrCreate([
            'phone' => $request->phone,
        ]);

        // Authenticate the user and generate a JWT token
        $token = auth('api')->login($user);

        // Return token details
        return [
            'user'=>$user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // in seconds
        ];
    }
}

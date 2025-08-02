<?php

namespace OtpLogin\Contracts;

use Illuminate\Http\Request;

/**
 * Interface OtpLoginHandlerInterface
 *
 * Defines the contract for OTP-based login handlers.
 * Implementations must handle the login logic based on the incoming request.
 */
interface OtpLoginHandlerInterface
{
    /**
     * Handle the OTP login process.
     *
     * @param Request $request The incoming HTTP request containing OTP credentials.
     * @return mixed The result of the login process (e.g., token, session, response).
     */
    public function login(Request $request): mixed;
}

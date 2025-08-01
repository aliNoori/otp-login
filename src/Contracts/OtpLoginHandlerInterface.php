<?php

namespace OtpLogin\Contracts;

use Illuminate\Http\Request;

interface OtpLoginHandlerInterface
{
    public function login(Request $request): mixed;
}

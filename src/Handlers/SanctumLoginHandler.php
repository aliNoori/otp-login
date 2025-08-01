<?php
namespace OtpLogin\Handlers;

use Illuminate\Http\Request;
use OtpLogin\Contracts\OtpLoginHandlerInterface;
use App\Models\User;

class SanctumLoginHandler implements OtpLoginHandlerInterface
{
    public function login(Request $request): mixed
    {
        $user = User::firstOrCreate([
            'phone' => $request->phone,
        ]);

        return [
            'token' => $user->createToken('otp-login')->plainTextToken,
            'user' => $user,
        ];
    }
}

<?php
namespace OtpLogin\Handlers;

use Illuminate\Http\Request;
use OtpLogin\Contracts\OtpLoginHandlerInterface;
use App\Models\User;

class JwtLoginHandler implements OtpLoginHandlerInterface
{
    public function login(Request $request): array
    {
        $user = User::firstOrCreate([
            'phone' => $request->phone,
        ]);

        $token = auth('api')->login($user);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }
}

<?php
namespace OtpLogin\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OtpLogin\Contracts\OtpLoginHandlerInterface;
use App\Models\User;

class SessionLoginHandler implements OtpLoginHandlerInterface
{
    public function login(Request $request): mixed
    {
        $user = User::firstOrCreate([
            'phone' => $request->phone,
        ]);

        Auth::login($user);

        return [
            'message' => 'User logged in using session.',
            'session_id' => session()->getId(), // optional
            'user' => $user,
        ];
    }
}

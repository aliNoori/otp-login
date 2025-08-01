<?php
namespace OtpLogin\Services;

use OtpLogin\Contracts\OtpLoginHandlerInterface;
use RuntimeException;

class LoginHandlerResolver
{
    public function resolve(): OtpLoginHandlerInterface
    {
        $handlerKey = config('otp-login.handler.key');
        $handlers = config('otp-login.handler.map');

        $handlerClass = $handlers[$handlerKey] ?? null;

        if (!is_string($handlerClass) || !class_exists($handlerClass)) {
            throw new RuntimeException("Invalid login handler: " . ($handlerKey ?? 'null'));
        }

        return app($handlerClass);
    }
}

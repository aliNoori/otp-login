<?php

namespace OtpLogin\Services;

use OtpLogin\Contracts\OtpLoginHandlerInterface;
use RuntimeException;

/**
 * Class LoginHandlerResolver
 *
 * Resolves the appropriate login handler based on the configuration.
 * Supports dynamic selection of JWT, Sanctum, or Session-based authentication.
 */
class LoginHandlerResolver
{
    /**
     * Resolve and instantiate the configured login handler.
     *
     * @return OtpLoginHandlerInterface
     *
     * @throws RuntimeException If the handler class is invalid or not found.
     */
    public function resolve(): OtpLoginHandlerInterface
    {
        // Retrieve the selected handler key from config
        $handlerKey = config('otp-login.handler.key');

        // Retrieve the map of available handlers
        $handlers = config('otp-login.handler.map');

        // Resolve the class name for the selected handler
        $handlerClass = $handlers[$handlerKey] ?? null;

        // Validate the class existence
        if (!is_string($handlerClass) || !class_exists($handlerClass)) {
            throw new RuntimeException("Invalid login handler: " . ($handlerKey ?? 'null'));
        }

        // Resolve the handler from the container
        return app($handlerClass);
    }
}

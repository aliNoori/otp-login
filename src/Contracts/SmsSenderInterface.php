<?php

namespace OtpLogin\Contracts;

/**
 * Interface SmsSenderInterface
 *
 * Defines the contract for sending SMS messages.
 * Implementations should integrate with specific SMS gateways or providers.
 */
interface SmsSenderInterface
{
    /**
     * Send an SMS message to the specified phone number.
     *
     * @param string $phoneNumber The recipient's mobile number (in international format if required).
     * @param string $message The message content to be sent.
     * @return bool True if the message was successfully dispatched; false otherwise.
     */
    public function send(string $phoneNumber, string $message): bool;
}

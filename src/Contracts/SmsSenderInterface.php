<?php
namespace OtpLogin\Contracts;

interface SmsSenderInterface
{
    public function send(string $phoneNumber, string $message): bool;
}


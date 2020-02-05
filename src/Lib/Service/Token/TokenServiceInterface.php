<?php

namespace Todo\Lib\Service\Token;

interface TokenServiceInterface
{
    public function getToken(): string;

    public function generateToken(string $secret, string $salt): void;

    public function isValidToken(string $token, string $secret): bool;
}

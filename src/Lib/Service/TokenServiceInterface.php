<?php

namespace Todo\Lib\Service;

interface TokenServiceInterface
{
    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @param string $secret
     * @param string $salt
     */
    public function generateToken(string $secret, string $salt): void;

    /**
     * @param string $token
     * @param string $secret
     *
     * @return bool
     */
    public function isValidToken(string $token, string $secret): bool;
}

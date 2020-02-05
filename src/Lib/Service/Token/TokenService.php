<?php

namespace Todo\Lib\Service\Token;

class TokenService implements TokenServiceInterface
{
    private string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    public function generateToken(string $secret, string $salt): void
    {
        $this->token = $this->generate($secret, $salt);
    }

    public function isValidToken(string $token, string $secret): bool
    {
        $salt = explode(':', $token)[0];

        return $token === $this->generate($secret, $salt);
    }

    private function generate(string $secret, string $salt): string
    {
        return $salt.':'.md5($salt.':'.$secret);
    }
}

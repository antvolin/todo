<?php

namespace BeeJeeMVC\Lib\Manager;

class TokenService implements TokenServiceInterface
{
    /**
     * @var string
     */
    private $token;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $secret
     * @param string $salt
     */
    public function generateToken(string $secret, string $salt): void
    {
        $this->token = $this->generate($secret, $salt);
    }

    /**
     * @param string $token
     * @param string $secret
     *
     * @return bool
     */
    public function isValidToken(string $token, string $secret): bool
    {
        $salt = explode(':', $token)[0];

        return $token === $this->generate($secret, $salt);
    }

    /**
     * @param string $salt
     * @param string $secret
     *
     * @return string
     */
    private function generate(string $secret, string $salt): string
    {
        return $salt.':'.md5($salt.':'.$secret);
    }
}

<?php

namespace BeeJeeMVC\Lib;

class TokenManager
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
     * @param string $salt
     * @param string $secret
     */
    public function generateToken(string $salt, string $secret): void
    {
        $this->token = $this->generate($salt, $secret);
    }

    /**
     * @param string $token
     * @param string $secret
     *
     * @return bool
     */
    public function checkToken(string $token, string $secret): bool
    {
        $salt = explode(':', $token)[0];

        return $token === $this->generate($salt, $secret);
    }

    /**
     * @param string $salt
     * @param string $secret
     *
     * @return string
     */
    private function generate(string $salt, string $secret): string
    {
        return $salt.':'.md5($salt.':'.$secret);
    }
}

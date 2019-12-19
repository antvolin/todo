<?php

namespace BeeJeeMVC\Lib;

use Symfony\Component\HttpFoundation\Request;

class TokenManager
{
    /**
     * @param Request $request
     * @param string $salt
     *
     * @return string
     */
    public function generateToken(Request $request, string $salt): string
    {
        if (!$request->getSession()->get('secret')) {
            $secret = $this->generateSecret();

            $request->getSession()->set('secret', $secret);
        } else {
            $secret = $request->getSession()->get('secret');
        }

        return $salt.':'.md5($salt.':'.$secret);
    }

    /**
     * @param string $token
     * @param Request $request
     *
     * @return bool
     */
    public function checkToken(string $token, Request $request): bool
    {
        $salt = explode(':', $token)[0];

        return $token === $salt.':'.md5($salt.':'.$request->getSession()->get('secret'));
    }

    /**
     * @return string
     */
    private function generateSecret(): string
    {
        return md5($_ENV['TOKEN_SECRET'].uniqid('secret', true));
    }
}

<?php

namespace BeeJeeMVC\Lib\Factory;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\TokenManager;
use Symfony\Component\HttpFoundation\Request;

class TokenManagerFactory
{
    /**
     * @param Request $request
     *
     * @return TokenManager
     */
    public function create(Request $request): TokenManager
    {
        $tokenManager = new TokenManager();

        if (!$secret = $request->getSession()->get('secret')) {
            $secret = (new App())->getSecret();

            $request->getSession()->set('secret', $secret);
        }

        $tokenManager->generateToken($secret, $_ENV['TOKEN_SALT']);

        return $tokenManager;
    }
}

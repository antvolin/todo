<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Manager\TokenManager;
use Symfony\Component\HttpFoundation\Request;

class TokenManagerFactory implements TokenManagerFactoryInterface
{
    /**
     * @var string
     */
    private $tokenSalt;

    /**
     * @param string $tokenSalt
     */
    public function __construct(string $tokenSalt)
    {
        $this->tokenSalt = $tokenSalt;
    }

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

        $tokenManager->generateToken($secret, $this->tokenSalt);

        return $tokenManager;
    }
}

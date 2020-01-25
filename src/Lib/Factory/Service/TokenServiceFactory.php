<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\App;
use Todo\Lib\Service\Token\TokenService;
use Symfony\Component\HttpFoundation\Request;

class TokenServiceFactory implements TokenServiceFactoryInterface
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
     * @return TokenService
     */
    public function create(Request $request): TokenService
    {
        $tokenManager = new TokenService();

        if (!$secret = $request->getSession()->get('secret')) {
            $secret = (new App())->getSecret();

            $request->getSession()->set('secret', $secret);
        }

        $tokenManager->generateToken($secret, $this->tokenSalt);

        return $tokenManager;
    }
}

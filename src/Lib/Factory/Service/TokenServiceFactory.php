<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\App;
use Todo\Lib\Service\Token\TokenService;
use Symfony\Component\HttpFoundation\Request;

class TokenServiceFactory implements TokenServiceFactoryInterface
{
    private string $tokenSalt;

    public function __construct(string $tokenSalt)
    {
        $this->tokenSalt = $tokenSalt;
    }

    public function create(Request $request): TokenService
    {
        $tokenService = new TokenService();

        if (!$secret = $request->getSession()->get('secret')) {
            $secret = (new App())->createSecret();

            $request->getSession()->set('secret', $secret);
        }

        $tokenService->generateToken($secret, $this->tokenSalt);

        return $tokenService;
    }
}

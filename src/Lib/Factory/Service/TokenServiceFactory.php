<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\App;
use Todo\Lib\Service\Token\TokenService;
use Symfony\Component\HttpFoundation\Request;

class TokenServiceFactory implements ServiceFactoryInterface
{
    private string $tokenSalt;
    private ?Request $request = null;

    public function __construct(string $tokenSalt)
    {
        $this->tokenSalt = $tokenSalt;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function createService(): TokenService
    {
        $tokenService = new TokenService();

        if (!$secret = $this->request->getSession()->get('secret')) {
            $secret = (new App())->createSecret();

            $this->request->getSession()->set('secret', $secret);
        }

        $tokenService->generateToken($secret, $this->tokenSalt);

        return $tokenService;
    }
}

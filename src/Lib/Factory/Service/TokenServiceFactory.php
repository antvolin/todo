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
     * @inheritDoc
     */
    public function __construct(string $tokenSalt)
    {
        $this->tokenSalt = $tokenSalt;
    }

    /**
     * @inheritDoc
     */
    public function create(Request $request): TokenService
    {
        $tokenService = new TokenService();

        if (!$secret = $request->getSession()->get('secret')) {
            $secret = (new App())->getSecret();

            $request->getSession()->set('secret', $secret);
        }

        $tokenService->generateToken($secret, $this->tokenSalt);

        return $tokenService;
    }
}

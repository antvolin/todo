<?php

namespace BeeJeeMVC\Lib\Factory\Service;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Service\TokenService;
use Symfony\Component\HttpFoundation\Request;

class TokenServiceService implements TokenManagerServiceInterface
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

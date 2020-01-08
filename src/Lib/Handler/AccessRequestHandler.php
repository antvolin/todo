<?php

namespace BeeJeeMVC\Lib\Handler;

use BeeJeeMVC\Lib\Factory\TokenManagerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessRequestHandler extends RequestHandler
{
    private const ACCESS_DENIED_MSG = 'Attempt to use csrf attack!';

    /**
     * @var TokenManagerFactory
     */
    private $tokenManagerFactory;

    public function __construct(TokenManagerFactory $tokenManagerFactory)
    {
        $this->tokenManagerFactory = $tokenManagerFactory;
    }

    /**
     * @param Request $request
     */
    protected function process(Request $request): void
    {
        $token = $request->get('csrf-token');
        $secret = $request->getSession()->get('secret');
        $tokenManager = $this->tokenManagerFactory->create($request);

        if ($token && !$tokenManager->isValidToken($token, $secret)) {
            throw new AccessDeniedHttpException(self::ACCESS_DENIED_MSG);
        }
    }
}

<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Todo\Lib\Factory\Service\TokenServiceFactoryInterface;

class AccessRequestHandlerService extends RequestHandlerService
{
    private const ACCESS_DENIED_MSG = 'Attempt to use csrf attack!';
    private TokenServiceFactoryInterface $tokenServiceFactory;

    public function __construct(?RequestHandlerService $nextHandler, TokenServiceFactoryInterface $tokenServiceFactory)
    {
        parent::__construct($nextHandler);

        $this->tokenServiceFactory = $tokenServiceFactory;
    }

    protected function process(Request $request): void
    {
        $token = $request->get('csrf-token');
        $secret = $request->getSession()->get('secret');
        $tokenService = $this->tokenServiceFactory->create($request);

        if ($token && !$tokenService->isValidToken($token, $secret)) {
            throw new AccessDeniedHttpException(self::ACCESS_DENIED_MSG);
        }
    }
}

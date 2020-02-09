<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Todo\Lib\Factory\Service\TokenServiceFactory;

class AccessRequestHandlerService extends RequestHandlerService
{
    private const ACCESS_DENIED_MSG = 'Attempt to use csrf attack!';
    private TokenServiceFactory $tokenServiceFactory;

    public function __construct(
        ?RequestHandlerService $nextHandler,
        TokenServiceFactory $tokenServiceFactory
    )
    {
        parent::__construct($nextHandler);

        $this->tokenServiceFactory = $tokenServiceFactory;
    }

    protected function process(Request $request): void
    {
        $token = $request->get('csrf-token');
        $secret = $request->getSession()->get('secret');
        $this->tokenServiceFactory->setRequest($request);
        $tokenService = $this->tokenServiceFactory->createService();

        if ($token && !$tokenService->isValidToken($token, $secret)) {
            throw new AccessDeniedHttpException(self::ACCESS_DENIED_MSG);
        }
    }
}

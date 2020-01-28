<?php

namespace Todo\Lib\Service\RequestHandler;

use Todo\Lib\Factory\Service\TokenServiceFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessRequestHandlerService extends RequestHandlerService
{
    private const ACCESS_DENIED_MSG = 'Attempt to use csrf attack!';

    /**
     * @var TokenServiceFactory
     */
    private $tokenServiceFactory;

    public function __construct(TokenServiceFactory $tokenServiceFactory)
    {
        $this->tokenServiceFactory = $tokenServiceFactory;
    }

    /**
     * @param Request $request
     */
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

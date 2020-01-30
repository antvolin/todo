<?php

namespace Todo\Lib\Service\RequestHandler;

use Todo\Lib\Factory\Service\TokenServiceFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessRequestHandlerService extends RequestHandlerService
{
    private const ACCESS_DENIED_MSG = 'Attempt to use csrf attack!';
    private TokenServiceFactory $tokenServiceFactory;

    /**
     * @param RequestHandlerService|null $requestHandlerService
     * @param TokenServiceFactory $tokenServiceFactory
     */
    public function __construct(?RequestHandlerService $requestHandlerService, TokenServiceFactory $tokenServiceFactory)
    {
        parent::__construct($requestHandlerService);

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

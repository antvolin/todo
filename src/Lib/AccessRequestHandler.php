<?php

namespace BeeJeeMVC\Lib;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessRequestHandler extends RequestHandler
{
    private const ATTEMPT_TO_USE_CSRF_ATTACK = 'Attempt to use csrf attack!';

    /**
     * @var TokenManager
     */
    private $tokenManager;

    /**
     * @param TokenManager $tokenManager
     */
    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param Request $request
     */
    protected function processing(Request $request): void
    {
        $token = $request->get('csrf-token');
        $secret = $request->getSession()->get('secret');

        if ($token && !$this->tokenManager->checkToken($token, $secret)) {
            throw new AccessDeniedHttpException(self::ATTEMPT_TO_USE_CSRF_ATTACK);
        }
    }
}

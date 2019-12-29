<?php

namespace BeeJeeMVC\Lib\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleRequestHandler extends RequestHandler
{
    private const NOT_ENOUGH_RIGHTS_MSG = 'Not enough rights for this operation!';
    private const MODIFY_METHODS = ['edit', 'done'];

    /**
     * @param Request $request
     */
    protected function processing(Request $request): void
    {
        $controllerMethodName = explode('/', ltrim($request->getPathInfo(), '/ '))[1];

        if (in_array($controllerMethodName, self::MODIFY_METHODS, true) && !$request->getSession()->get('admin')) {
            throw new AccessDeniedHttpException(self::NOT_ENOUGH_RIGHTS_MSG);
        }
    }
}

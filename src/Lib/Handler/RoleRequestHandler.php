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
    protected function process(Request $request): void
    {
        // TODO: need encapsulate this logic
        $pathParts = explode('/', ltrim($request->getPathInfo(), '/'));

        if (count($pathParts) > 1) {
            $controllerAction = strtolower($pathParts[1]);
            $admin = $request->getSession()->get('admin');

            if (!$admin && in_array($controllerAction, self::MODIFY_METHODS, true)) {
                throw new AccessDeniedHttpException(self::NOT_ENOUGH_RIGHTS_MSG);
            }
        }
    }
}

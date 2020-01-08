<?php

namespace BeeJeeMVC\Lib\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleRequestHandler extends RequestHandler
{
    public const MODIFY_METHODS = [
        self::EDIT_METHOD,
        self::DONE_METHOD,
    ];
    private const EDIT_METHOD = 'edit';
    private const DONE_METHOD = 'done';
    private const NOT_ENOUGH_RIGHTS_MSG = 'Not enough rights for this operation!';

    /**
     * @param Request $request
     */
    protected function process(Request $request): void
    {
        // TODO: need encapsulate this logic
        $pathParts = explode('/', ltrim($request->getPathInfo(), '/'));

        if (count($pathParts) > 1) {
            $controllerMethodName = strtolower($pathParts[1]);
            $admin = $request->getSession()->get('admin');

            if (!$admin && in_array($controllerMethodName, self::MODIFY_METHODS, true)) {
                throw new AccessDeniedHttpException(self::NOT_ENOUGH_RIGHTS_MSG);
            }
        }
    }
}

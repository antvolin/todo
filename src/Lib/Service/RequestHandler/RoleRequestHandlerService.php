<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Todo\Lib\Service\Path\PathService;

class RoleRequestHandlerService extends RequestHandlerService
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
        $pathParts = PathService::separatePath($request->getPathInfo());

        if (count($pathParts) > 1) {
            $controllerMethodName = PathService::getFirstPathPart($request->getPathInfo());
            $admin = $request->getSession()->get('admin');

            if (!$admin && in_array($controllerMethodName, self::MODIFY_METHODS, true)) {
                throw new AccessDeniedHttpException(self::NOT_ENOUGH_RIGHTS_MSG);
            }
        }
    }
}

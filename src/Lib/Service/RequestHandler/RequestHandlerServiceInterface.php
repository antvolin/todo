<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerServiceInterface
{
    /**
     * @param Request $request
     */
    public function handle(Request $request): void;
}

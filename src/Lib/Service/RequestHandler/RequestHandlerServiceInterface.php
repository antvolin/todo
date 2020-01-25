<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerServiceInterface
{
    /**
     * @param RequestHandlerService $handler
     *
     * @return RequestHandlerService
     */
    public function setNextHandler(RequestHandlerService $handler): RequestHandlerService;

    /**
     * @param Request $request
     */
    public function handle(Request $request): void;
}

<?php

namespace Todo\Lib\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerInterface
{
    /**
     * @param RequestHandler $handler
     *
     * @return RequestHandler
     */
    public function setNextHandler(RequestHandler $handler): RequestHandler;

    /**
     * @param Request $request
     */
    public function handle(Request $request): void;
}

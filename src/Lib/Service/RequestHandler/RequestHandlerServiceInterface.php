<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerServiceInterface
{
    public function handle(Request $request): void;
}

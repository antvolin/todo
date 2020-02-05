<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

abstract class RequestHandlerService implements RequestHandlerServiceInterface
{
    protected ?RequestHandlerService $nextHandler = null;

    public function __construct(?RequestHandlerService $nextHandler = null)
    {
        $this->nextHandler = $nextHandler;
    }

    public function handle(Request $request): void
    {
        $this->process($request);

        if ($this->nextHandler) {
            $this->nextHandler->handle($request);
        }
    }

    abstract protected function process(Request $request): void;
}

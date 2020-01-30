<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

abstract class RequestHandlerService implements RequestHandlerServiceInterface
{
    protected ?RequestHandlerService $nextHandler = null;

    /**
     * @param RequestHandlerService|null $handler
     */
    public function __construct(?RequestHandlerService $handler = null)
    {
        $this->nextHandler = $handler;
    }

    /**
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        $this->process($request);

        if ($this->nextHandler) {
            $this->nextHandler->handle($request);
        }
    }

    /**
     * @param Request $request
     */
    abstract protected function process(Request $request): void;
}

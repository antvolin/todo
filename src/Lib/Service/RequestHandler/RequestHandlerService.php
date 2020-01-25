<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

abstract class RequestHandlerService implements RequestHandlerServiceInterface
{
    /**
     * @var RequestHandlerService
     */
    protected $nextHandler;

    /**
     * @param RequestHandlerService $handler
     *
     * @return RequestHandlerService
     */
    public function setNextHandler(RequestHandlerService $handler): RequestHandlerService
    {
        $this->nextHandler = $handler;

        return $handler;
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

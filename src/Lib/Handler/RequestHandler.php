<?php

namespace BeeJeeMVC\Lib\Handler;

use Symfony\Component\HttpFoundation\Request;

abstract class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var RequestHandler
     */
    protected $nextHandler;

    /**
     * @param RequestHandler $handler
     *
     * @return RequestHandler
     */
    public function setNextHandler(RequestHandler $handler): RequestHandler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    /**
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        $this->processing($request);

        if ($this->nextHandler) {
            $this->nextHandler->handle($request);
        }
    }

    /**
     * @param Request $request
     */
    abstract protected function processing(Request $request): void;
}

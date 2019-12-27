<?php

namespace BeeJeeMVC\Lib;

use Symfony\Component\HttpFoundation\Request;

abstract class RequestHandler
{
    /**
     * @var RequestHandler
     */
    protected $next;

    /**
     * @param RequestHandler $handler
     *
     * @return RequestHandler
     */
    public function setNext(RequestHandler $handler): RequestHandler
    {
        $this->next = $handler;

        return $handler;
    }

    /**
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        $this->processing($request);

        if ($this->next) {
            $this->next->handle($request);
        }
    }

    /**
     * @param Request $request
     */
    abstract protected function processing(Request $request): void;
}

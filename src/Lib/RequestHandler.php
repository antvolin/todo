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
     *
     * @return bool
     */
    public function handle(Request $request): bool
    {
        $processed = $this->processing($request);

        if (!$processed && $this->next) {
            $processed = $this->next->handle($request);
        }

        return $processed;
    }

    abstract protected function processing(Request $request): bool;
}

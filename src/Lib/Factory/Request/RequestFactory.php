<?php

namespace Todo\Lib\Factory\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class RequestFactory implements RequestFactoryInterface
{
    /**
     * @return Request
     */
    public function create(): Request
    {
        $request = Request::createFromGlobals();

        if (!$request->hasSession()) {
            $request->setSession(new Session());
        }

        return $request;
    }
}

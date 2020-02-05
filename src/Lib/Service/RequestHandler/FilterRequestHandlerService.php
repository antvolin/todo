<?php

namespace Todo\Lib\Service\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

class FilterRequestHandlerService extends RequestHandlerService
{
    protected function process(Request $request): void
    {
        if ($request->request->count()) {
            foreach ($request->request->keys() as $key) {
                $value = $request->request->filter($key, null, FILTER_SANITIZE_SPECIAL_CHARS);
                $request->request->set($key, $value);
            }
        }
    }
}

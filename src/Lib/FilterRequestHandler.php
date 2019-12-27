<?php

namespace BeeJeeMVC\Lib;

use Symfony\Component\HttpFoundation\Request;

class FilterRequestHandler extends RequestHandler
{
    /**
     * @param Request $request
     */
    protected function processing(Request $request): void
    {
        if ($request->request->count()) {
            foreach ($request->request->keys() as $key) {
                $value = $request->request->filter($key, null, FILTER_SANITIZE_SPECIAL_CHARS);
                $request->request->set($key, $value);
            }
        }
    }
}

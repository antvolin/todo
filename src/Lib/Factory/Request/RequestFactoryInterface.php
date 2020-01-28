<?php

namespace Todo\Lib\Factory\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    /**
     * @return Request
     */
    public function create(): Request;
}

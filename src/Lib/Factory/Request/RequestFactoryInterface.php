<?php

namespace Todo\Lib\Factory\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    public function create(): Request;
}

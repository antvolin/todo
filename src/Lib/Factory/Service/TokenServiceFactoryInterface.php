<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\Token\TokenService;
use Symfony\Component\HttpFoundation\Request;

interface TokenServiceFactoryInterface
{
    public function __construct(string $tokenSalt);

    public function create(Request $request): TokenService;
}

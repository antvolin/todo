<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\Token\TokenService;
use Symfony\Component\HttpFoundation\Request;

interface TokenServiceFactoryInterface
{
    /**
     * @param string $tokenSalt
     */
    public function __construct(string $tokenSalt);

    /**
     * @param Request $request
     *
     * @return TokenService
     */
    public function create(Request $request): TokenService;
}

<?php

namespace Todo\Lib\Factory\Service;

use Todo\Lib\Service\TokenService;
use Symfony\Component\HttpFoundation\Request;

interface TokenManagerServiceInterface
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

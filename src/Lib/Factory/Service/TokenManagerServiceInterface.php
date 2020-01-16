<?php

namespace BeeJeeMVC\Lib\Factory\Service;

use BeeJeeMVC\Lib\Service\TokenService;
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

<?php

namespace BeeJeeMVC\Lib\Factory\Manager;

use BeeJeeMVC\Lib\Manager\TokenManager;
use Symfony\Component\HttpFoundation\Request;

interface TokenManagerFactoryInterface
{
    /**
     * @param string $tokenSalt
     */
    public function __construct(string $tokenSalt);

    /**
     * @param Request $request
     *
     * @return TokenManager
     */
    public function create(Request $request): TokenManager;
}

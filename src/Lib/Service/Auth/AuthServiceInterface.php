<?php

namespace Todo\Lib\Service\Auth;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface AuthServiceInterface
{
    public function __construct(Request $request, string $user, string $password);

    public function getRequest(): Request;

    public function login(): ?RedirectResponse;

    public function logout(): RedirectResponse;
}

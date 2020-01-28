<?php

namespace Todo\Lib\Service\Auth;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface AuthServiceInterface
{
    /**
     * @param Request $request
     * @param string $user
     * @param string $password
     */
    public function __construct(Request $request, string $user, string $password);

    /**
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * @return RedirectResponse|null
     */
    public function login(): ?RedirectResponse;

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse;
}

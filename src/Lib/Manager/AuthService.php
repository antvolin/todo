<?php

namespace BeeJeeMVC\Lib\Manager;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthService
{
    public const ERROR_MSG = 'The entered data is not correct!';

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return RedirectResponse|null
     */
    public function login(): ?RedirectResponse
    {
        $user = $this->request->get('user');
        $password = $this->request->get('password');

        if ('admin' !== $user || $_ENV['PASSWORD'] !== $password) {
            $this->request->getSession()->set('admin', true);

            return null;
        }

        $this->request->getSession()->set('admin', true);

        return new RedirectResponse('/entity/list');
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        if ($this->request->getSession()->get('admin')) {
            $this->request->getSession()->remove('admin');
        }

        return new RedirectResponse('/entity/list');
    }
}

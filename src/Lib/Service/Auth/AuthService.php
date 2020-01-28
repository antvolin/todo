<?php

namespace Todo\Lib\Service\Auth;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthService implements AuthServiceInterface
{
    public const ERROR_MSG = 'The entered data is not correct!';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @inheritDoc
     */
    public function __construct(Request $request, string $user, string $password)
    {
        $this->request = $request;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function login(): ?RedirectResponse
    {
        $user = $this->request->get('user');
        $password = $this->request->get('password');

        if ($this->user !== $user || $this->password !== $password) {
            return null;
        }

        $this->request->getSession()->set($this->user, true);

        return new RedirectResponse('/entity/list');
    }

    /**
     * @inheritDoc
     */
    public function logout(): RedirectResponse
    {
        if ($this->request->getSession()->get($this->user)) {
            $this->request->getSession()->remove($this->user);
        }

        return new RedirectResponse('/entity/list');
    }
}

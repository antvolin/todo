<?php

namespace BeeJeeMVC;

use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    /**
     * @var Request
     */
    private $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    public function login(): void
    {
        if ('POST' === $this->request->getMethod()) {
            $user = $this->request->get('user');
            $password = (int) $this->request->get('password');

            if ('admin' === $user && 123 === $password) {
                $_SESSION['admin'] = true;

                include_once('login_success.html');
            } else {
                $error = 'The entered data is not correct!';

                include_once('login.html');
            }
        } else {
            include_once('login.html');
        }
    }

    public function logout(): void
    {
        if ($_SESSION['admin']) {
            unset($_SESSION['admin']);
        }

        include_once('logout_success.html');
    }
}

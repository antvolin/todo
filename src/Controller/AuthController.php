<?php

namespace BeeJeeMVC\Controller;

use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function login(): void
    {
        if ('POST' === $this->request->getMethod()) {
            $user = $this->request->get('user');
            $password = $this->request->request->getInt('password');

            if ('admin' === $user && 123 === $password) {
                $_SESSION['admin'] = true;

                include_once(dirname(__DIR__).'/View/login_success.html');
            } else {
                $error = 'The entered data is not correct!';

                include_once(dirname(__DIR__) . '/View/form_login.html');
            }
        } else {
            include_once(dirname(__DIR__) . '/View/form_login.html');
        }
    }

    public function logout(): void
    {
        if ($_SESSION['admin']) {
            unset($_SESSION['admin']);
        }

        include_once(dirname(__DIR__).'/View/logout_success.html');
    }
}

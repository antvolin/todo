<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Template;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Template
     */
    private $template;

    public function __construct(Request $request, Template $template)
    {
        $this->request = $request;
        $this->template = $template;
    }

    public function login(): void
    {
        if ('POST' !== $this->request->getMethod()) {
            echo $this->template->render('form_login');

            return;
        }

        $user = $this->request->get('user');
        $password = $this->request->request->getInt('password');

        if ('admin' === $user && 123 === $password) {
            $_SESSION['admin'] = true;

            echo $this->template->render('login_success');
        } else {
            echo $this->template->render('form_login', ['error' => 'The entered data is not correct!']);
        }
    }

    public function logout(): void
    {
        if ($_SESSION['admin']) {
            unset($_SESSION['admin']);
        }

        echo $this->template->render('logout_success');
    }
}

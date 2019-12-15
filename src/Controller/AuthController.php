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

    /**
     * @param Request $request
     * @param Template $template
     */
    public function __construct(Request $request, Template $template)
    {
        $this->request = $request;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function login(): ?string
    {
        if ('POST' !== $this->request->getMethod()) {
            return $this->template->render('form_login');
        }

        $user = $this->request->get('user');
        $password = $this->request->request->getInt('password');

        if ('admin' === $user && 123 === $password) {
            // $this->request->getSession()->set('admin', true);
            $_SESSION['admin'] = true;

            return $this->template->render('login_success');
        }

        return $this->template->render('form_login', ['error' => 'The entered data is not correct!']);
    }

    /**
     * @return string
     */
    public function logout(): string
    {
        if ($this->request->getSession()->get('admin')) {
            unset($_SESSION['admin']);
        }

        return $this->template->render('logout_success');
    }
}

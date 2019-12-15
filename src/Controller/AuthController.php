<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function login(): Response
    {
        if ('POST' !== $this->request->getMethod()) {
            return new Response($this->template->render('form_login'));
        }

        $user = $this->request->get('user');
        $password = $this->request->request->getInt('password');

        if ('admin' === $user && $_ENV['PASSWORD'] === $password) {
            $_SESSION['admin'] = true;

            return new Response($this->template->render('login_success'));
        }

        return new Response($this->template->render('form_login', ['error' => 'The entered data is not correct!']));
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        if ($this->request->getSession()->get('admin')) {
            unset($_SESSION['admin']);
        }

        return new Response($this->template->render('logout_success'));
    }
}

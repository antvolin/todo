<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @return RedirectResponse|Response
     */
    public function login()
    {
        if ('POST' !== $this->request->getMethod()) {
            return new Response($this->template->render('form_login'));
        }

        $user = $this->request->get('user');
        $password = $this->request->get('password');

        if ('admin' === $user && $_ENV['PASSWORD'] === $password) {
            $this->request->getSession()->set('admin', true);

            return new RedirectResponse('/task/list');
        }

        return new Response($this->template->render('form_login', ['error' => 'The entered data is not correct!']));
    }

    /**
     * @return RedirectResponse
     */
    public function logout()
    {
        if ($this->request->getSession()->get('admin')) {
            $this->request->getSession()->remove('admin');
        }

        return new RedirectResponse('/task/list');
    }
}

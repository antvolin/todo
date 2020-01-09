<?php

namespace BeeJeeMVC\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AuthController
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @param string $token
     * @param Request $request
     * @param Environment $template
     */
    public function __construct(
        string $token,
        Request $request,
        Environment $template
    )
    {
        $this->token = $token;
        $this->request = $request;
        $this->template = $template;
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login()
    {
        if ('POST' !== $this->request->getMethod()) {
            $params = ['token' => $this->token];

            return new Response($this->template->render('form_login.html.twig', $params));
        }

        $user = $this->request->get('user');
        $password = $this->request->get('password');

        if ('admin' === $user && $_ENV['PASSWORD'] === $password) {
            $this->request->getSession()->set('admin', true);

            return new RedirectResponse('/task/list');
        }

        $params = ['error' => 'The entered data is not correct!', 'token' => $this->token];

        return new Response($this->template->render('form_login.html.twig', $params));
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        if ($this->request->getSession()->get('admin')) {
            $this->request->getSession()->remove('admin');
        }

        return new RedirectResponse('/task/list');
    }
}

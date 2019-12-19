<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Template;
use BeeJeeMVC\Lib\TokenManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    private const ATTEMPT_TO_USE_CSRF_ATTACK = 'Attempt to use csrf attack!';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Template
     */
    private $template;

    /**
     * @var string
     */
    private $token;

    /**
     * @param Request $request
     * @param Template $template
     * @param string $token
     */
    public function __construct(Request $request, Template $template, string $token)
    {
        $this->request = $request;
        $this->template = $template;
        $this->token = $token;
    }

    /**
     * @return RedirectResponse|Response
     */
    public function login()
    {
        if ('POST' !== $this->request->getMethod()) {
            $args = ['token' => $this->token];

            return new Response($this->template->render('form_login', $args));
        }

        if (!(new TokenManager())->checkToken($this->request->get('csrf-token'), $this->request)) {
            return new Response(self::ATTEMPT_TO_USE_CSRF_ATTACK, Response::HTTP_FORBIDDEN);
        }

        $user = $this->request->get('user');
        $password = $this->request->get('password');

        if ('admin' === $user && $_ENV['PASSWORD'] === $password) {
            $this->request->getSession()->set('admin', true);

            return new RedirectResponse('/task/list');
        }

        $args = ['error' => 'The entered data is not correct!', 'token' => $this->token];

        return new Response($this->template->render('form_login', $args));
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

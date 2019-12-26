<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\TokenManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AuthController
{
    private const ATTEMPT_TO_USE_CSRF_ATTACK = 'Attempt to use csrf attack!';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var TokenManager
     */
    private $tokenManager;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @param Request $request
     * @param TokenManager $tokenManager
     * @param Environment $template
     */
    public function __construct(Request $request, TokenManager $tokenManager, Environment $template)
    {
        $this->request = $request;
        $this->tokenManager = $tokenManager;
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
            $args = ['token' => $this->tokenManager->getToken()];

            return new Response($this->template->render('form_login.html.twig', $args));
        }

        if (!$this->tokenManager->checkToken($this->request->get('csrf-token'), $this->request->getSession()->get('secret'))) {
            return new Response(self::ATTEMPT_TO_USE_CSRF_ATTACK, Response::HTTP_FORBIDDEN);
        }

        $user = $this->request->get('user');
        $password = $this->request->get('password');

        if ('admin' === $user && $_ENV['PASSWORD'] === $password) {
            $this->request->getSession()->set('admin', true);

            return new RedirectResponse('/task/list');
        }

        $params = ['error' => 'The entered data is not correct!', 'token' => $this->tokenManager->getToken()];

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

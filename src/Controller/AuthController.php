<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Manager\AuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AuthController
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @param AuthService $authService
     * @param Environment $template
     */
    public function __construct(
        AuthService $authService,
        Environment $template
    )
    {
        $this->authService = $authService;
        $this->template = $template;
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function login()
    {
        $token = $this->authService->getRequest()->get('token');

        if ('POST' !== $this->authService->getRequest()->getMethod()) {
            return new Response($this->template->render('form_login.html.twig', ['token' => $token]));
        }

        $response = $this->authService->login();

        if ($response) {
            return $response;
        }

        return new Response($this->template->render('form_login.html.twig', ['error' => AuthService::ERROR_MSG, 'token' => $token]));
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        return $this->authService->logout();
    }
}

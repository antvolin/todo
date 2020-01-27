<?php

namespace Todo\Controller;

use Todo\Lib\Factory\Template\TemplateAdapterInterface;
use Todo\Lib\Service\Auth\AuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var TemplateAdapterInterface
     */
    private $template;

    /**
     * @param AuthService $authService
     * @param TemplateAdapterInterface $template
     */
    public function __construct(
        AuthService $authService,
        TemplateAdapterInterface $template
    )
    {
        $this->authService = $authService;
        $this->template = $template;
    }

    /**
     * @return RedirectResponse|Response
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

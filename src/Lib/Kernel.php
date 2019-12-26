<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Controller\AuthController;
use BeeJeeMVC\Controller\TaskController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Kernel
{
    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function process(): void
    {
        $request = Request::createFromGlobals();
        $request->setSession($this->initSession());

        $tokenManager = new TokenManager();
        $tokenManager->generateToken($this->initSecretKey($request));

        $urlParts = explode('/', trim($request->getPathInfo(), '/'));

        if ('auth' === strtolower(array_shift($urlParts))) {
            $controller = new AuthController($request, $tokenManager, $this->createTemplate());
        } else {
            $taskRepo = new TaskFileRepository(dirname(__DIR__).'/../'.$_ENV['TASK_FOLDER_NAME']);
            $controller = new TaskController($request, new TaskManager($taskRepo), $tokenManager, new Paginator(), new Sorting(), $this->createTemplate());
        }

        if (method_exists($controller, $action = array_shift($urlParts))) {
            /** @var Response $response */
            $response = $controller->$action(...$urlParts);
        } else {
            $response = $controller->list();
        }

        $response->send();
	}

    /**
     * @return Session
     */
	private function initSession(): Session
    {
        $session = new Session();
        $session->start();

        return $session;
    }

    /**
     * @return Environment
     */
	private function createTemplate(): Environment
    {
        $loader = new FilesystemLoader(dirname(__DIR__).'/../'.'templates');
        $options = ['autoescape' => false];

        return new Environment($loader, $options);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
	private function initSecretKey(Request $request): string
    {
        if (!$request->getSession()->get('secret')) {
            $secret = (new SecretGenerator())->generateSecret();

            $request->getSession()->set('secret', $secret);
        } else {
            $secret = $request->getSession()->get('secret');
        }

        return $secret;
    }
}

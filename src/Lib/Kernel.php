<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Controller\AuthController;
use BeeJeeMVC\Controller\TaskController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Kernel
{
	public function process(): void
    {
        $request = Request::createFromGlobals();
        $session = new Session();
        $session->start();
        $request->setSession($session);

        $template = new Template();
        $taskRepo = new TaskFileRepository(dirname(__DIR__).'/../'.$_ENV['TASK_FOLDER_NAME']);

        if (!$request->getSession()->get('secret')) {
            $secret = (new SecretGenerator())->generateSecret();

            $request->getSession()->set('secret', $secret);
        } else {
            $secret = $request->getSession()->get('secret');
        }

        $tokenManager = new TokenManager();
        $tokenManager->generateToken($_ENV['TOKEN_SALT'], $secret);

        $urlParts = explode('/', trim($request->getPathInfo(), '/'));

        if ('auth' === strtolower(array_shift($urlParts))) {
            $controller = new AuthController($request, $template, $tokenManager);
        } else {
            $taskManager = new TaskManager($taskRepo);
            $isAdmin = $request->getSession()->get('admin', false);
            $isCreated = $request->getSession()->get('isCreated', false);
            $templateBuilder = new TemplateBuilder($taskRepo, $isAdmin, $isCreated);
            $controller = new TaskController($taskManager, $request, $template, $templateBuilder, $tokenManager);
        }

        $action = array_shift($urlParts);

        if (method_exists($controller, $action)) {
            /** @var Response $response */
            $response = $controller->$action(...$urlParts);
        } else {
            $response = $controller->list();
        }

        $response->send();
	}
}

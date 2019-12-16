<?php

namespace BeeJeeMVC\Lib;

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
        $taskFolderPath = dirname(__DIR__).$_ENV['TASK_FOLDER_NAME'];
        $taskRepo = new TaskFileRepository($taskFolderPath);
        $taskManager = new TaskManager($taskRepo);
        $isAdmin = $request->getSession()->get('admin', false);
        $templateBuilder = new TemplateBuilder($taskRepo, $isAdmin);

		if (!empty($request->query->get('route'))) {
            $controller = null;
			$urlParts = explode('/', trim($request->query->get('route'), '/'));
			$name = strtolower(array_shift($urlParts));
			$controllerName = 'BeeJeeMVC\\Controller\\'.ucfirst($name).'Controller';

			if (class_exists($controllerName)) {
			    if ('task' === $name) {
                    $controller = new $controllerName($taskManager, $request, $template, $templateBuilder);
                } else {
                    $controller = new $controllerName($request, $template);
                }
			}

            $action = array_shift($urlParts);

			if (method_exists($controller, $action)) {
                /** @var Response $response */
			    $response = $controller->$action(...$urlParts);
                $response->send();
			}
		}
	}
}

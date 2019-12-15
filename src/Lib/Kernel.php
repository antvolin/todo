<?php

namespace BeeJeeMVC\Lib;

use Symfony\Component\HttpFoundation\Request;

class Kernel
{
	public function process(): void
    {
        $request = Request::createFromGlobals();
        $template = new Template();
        $taskManager = new TaskManager(new TaskFileRepository());

		if (!empty($request->query->get('route'))) {
            $controller = null;
			$urlParts = explode('/', trim($request->query->get('route'), '/'));
			$name = strtolower(array_shift($urlParts));
			$controllerName = 'BeeJeeMVC\\Controller\\'.ucfirst($name).'Controller';

			if (class_exists($controllerName)) {
			    if ('task' === $name) {
                    $controller = new $controllerName($taskManager, $request, $template);
                } else {
                    $controller = new $controllerName($request, $template);
                }
			}

            $action = array_shift($urlParts);

			if (method_exists($controller, $action)) {
				$controller->$action(...$urlParts);
			}
		}
	}
}

<?php

namespace BeeJeeMVC\Lib;

use Symfony\Component\HttpFoundation\Request;

class Kernel
{
	public function process(): void
    {
		if (isset($_GET['route']) && !empty($_GET['route'])) {
            $controller = null;
			$urlParts = explode('/', trim($_GET['route'], '/'));
			$name = strtolower(array_shift($urlParts));
			$controllerName = 'BeeJeeMVC\\Controller\\'.ucfirst($name).'Controller';

			if (class_exists($controllerName)) {
                $request = Request::createFromGlobals();

			    if ('task' === $name) {
                    $controller = new $controllerName(new TaskFileRepository(), $request);
                } else {
                    $controller = new $controllerName($request);
                }
			}

            $action = array_shift($urlParts);

			if (method_exists($controller, $action)) {
				$controller->$action(...$urlParts);
			}
		}
	}
}

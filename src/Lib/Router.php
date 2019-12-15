<?php

namespace BeeJeeMVC\Lib;

class Router
{
	public function process(): void
    {
		if (isset($_GET['route']) && !empty($_GET['route'])) {
            $controller = null;
			$urlParts = explode('/', trim($_GET['route'], '/'));
			$controllerName = 'BeeJeeMVC\\Controller\\'.ucfirst(strtolower(array_shift($urlParts))).'Controller';

			if (class_exists($controllerName)) {
				$controller = new $controllerName();
			}

            $action = array_shift($urlParts);

			if (method_exists($controller, $action)) {
				$controller->$action(...$urlParts);
			}
		}
	}
}

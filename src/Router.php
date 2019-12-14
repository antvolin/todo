<?php

namespace BeeJeeMVC;

class Router
{
	public function process(): void
    {
		if (isset($_GET['route']) && !empty($_GET['route'])) {
			$urlParts = explode('/', trim($_GET['route'], '/'));

			$controllerName = 'BeeJeeMVC\\'.ucfirst(strtolower(array_shift($urlParts))).'Controller';
			$action = array_shift($urlParts);

			if (class_exists($controllerName)) {
				$controller = new $controllerName();
			}

			if (method_exists($controller, $action)) {
				$controller->$action($urlParts);
			}
		}
	}
}

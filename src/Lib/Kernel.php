<?php

namespace BeeJeeMVC\Lib;

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
			    if ('task' === $name) {
                    $controller = new $controllerName(new TaskFileRepository());
                } else {
                    $controller = new $controllerName();
                }
			}

            $action = array_shift($urlParts);

			if (method_exists($controller, $action)) {
				$controller->$action(...$urlParts);
			}
		}
	}
}

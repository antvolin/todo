<?php

require dirname(__DIR__).'/vendor/autoload.php';

use BeeJeeMVC\Router;

$router = new Router();
$router->process();

<?php

require dirname(__DIR__).'/vendor/autoload.php';

use BeeJeeMVC\Lib\Router;

session_start();

$router = new Router();
$router->process();

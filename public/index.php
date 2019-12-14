<?php

require dirname(__DIR__).'/vendor/autoload.php';

use BeeJeeMVC\Router;

session_start();

$router = new Router();
$router->process();

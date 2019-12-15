<?php

require dirname(__DIR__).'/vendor/autoload.php';

use BeeJeeMVC\Lib\Kernel;

session_start();

$router = new Kernel();
$router->process();

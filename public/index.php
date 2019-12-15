<?php

require dirname(__DIR__).'/vendor/autoload.php';

use BeeJeeMVC\Lib\Kernel;

$router = new Kernel();
$router->process();

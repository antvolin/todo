<?php

include_once '../config/bootstrap.php';

use BeeJeeMVC\Lib\Kernel;

$router = new Kernel();
$router->process();

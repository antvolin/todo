<?php

include_once '../config/bootstrap.php';

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Kernel;

$app = new App();
$kernel = new Kernel($app);
$kernel->process();

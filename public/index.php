<?php

include_once '../config/bootstrap.php';

use Todo\Lib\App;
use Todo\Lib\Kernel;

$app = new App();
$kernel = new Kernel($app);
$kernel->process();

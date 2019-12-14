<?php

require dirname(__DIR__).'/vendor/autoload.php';

use BeeJeeMVC\PageController;

$pageController = new PageController();

//$pageController->taskList();
$pageController->create();

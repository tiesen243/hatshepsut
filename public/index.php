<?php

use Framework\Application;

require_once __DIR__.'/../vendor/autoload.php';

$application = new Application(dirname(__DIR__));
$application->run();

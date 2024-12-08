<?php

require_once 'vendor/autoload.php';

use Calc\Kernel;

$kernel = new Kernel();

$application = $kernel->getConsoleApplication();

$application->run();

#!/usr/bin/php
<?php

use Net\Bazzline\ClassmapGenerator\Application;
//use Net\Bazzline\ClassmapGenerator\Configuration;
use Net\Bazzline\ClassmapGenerator\Command\CreateCommand;

require __DIR__ . '/../autoloader.php';

$files = array(
    __DIR__ . '/../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
);

foreach ($files as $file) {
    if (file_exists($file)) {
        require_once $file;
        break;
    }
}

$configuration = new Config(getcwd());

if ($configuration->hasBootstrapPathname()) {
    require_once $configuration->getBootstrapPathname();
}

$application = new Application($configuration);
$application->run();
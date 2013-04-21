#!/usr/bin/php
<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */

chdir(realpath(__DIR__ . DIRECTORY_SEPARATOR));

require '../src/Net/Bazzline/ClassmapGenerator/basicAutoloader.php';
require '../vendor/autoload.php';

use Net\Bazzline\ClassmapGenerator\Command\ManualCommand;
use Symfony\Component\Console\Application;

$application = new \Net\Bazzline\ClassmapGenerator\Application\Application(getcwd());
//$application->add(new ManualCommand());
$application->run();

/*
$userWorkingDirectory = getcwd();
//make everything relative to the application root
chdir(realpath(__DIR__ . DIRECTORY_SEPARATOR));
require_once __DIR__ . '/../basicAutoloader.php';

Application\Application::create($userWorkingDirectory)
    ->andRun();

$application = new Application($configuration);
$application->run();
*/
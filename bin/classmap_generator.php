#!/usr/bin/php
<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
namespace Net\Bazzline\ClassmapGenerator;


use Net\Bazzline\ClassmapGenerator\Command\HelpCommand;
use Symfony\Component\Console\Application;

chdir(realpath(__DIR__ . DIRECTORY_SEPARATOR));

require '../vendor/autoload.php';
require '../src/Net/Bazzline/ClassmapGenerator/Command/HelpCommand.php';

$application = new Application();
$application->add(new HelpCommand());
$application->run();

/*
$userWorkingDirectory = getcwd();
//make everything relative to the application root
chdir(realpath(__DIR__ . DIRECTORY_SEPARATOR));
require_once __DIR__ . '/../autoloader.php';

Application\Application::create($userWorkingDirectory)
    ->andRun();

$application = new Application($configuration);
$application->run();
*/
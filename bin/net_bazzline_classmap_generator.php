#!/usr/bin/php
<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */

chdir(realpath(getcwd()));

//autloader for development
if (file_exists('src/Net/Bazzline/ClassmapGenerator/basicAutoloader.php')) {
    echo 'Development mode.' . PHP_EOL;
    echo 'Loading autoloaders' . PHP_EOL;

    require 'vendor/autoload.php';
    require 'src/Net/Bazzline/ClassmapGenerator/basicAutoloader.php';
}

$application = \Net\Bazzline\ClassmapGenerator\Application\Application::create(getcwd());
$application->run();

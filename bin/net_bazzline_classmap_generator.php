#!/usr/bin/php
<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */

chdir(realpath(getcwd()));

require 'vendor/autoload.php';

//autloader for development
if (file_exists('src/Net/Bazzline/ClassmapGenerator/basicAutoloader.php')) {
    echo 'Development mode.' . PHP_EOL;
    echo 'Loading autoloaders' . PHP_EOL;

    require 'src/Net/Bazzline/ClassmapGenerator/basicAutoloader.php';
}

$application = \Net\Bazzline\ClassmapGenerator\Application\Application::create(getcwd());
$application->run();

#!/usr/bin/php
<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */

chdir(realpath(getcwd()));

//autloader for development
if (file_exists('src/Net/Bazzline/ClassmapGenerator/basicAutoloader.php')) {
    require 'src/Net/Bazzline/ClassmapGenerator/basicAutoloader.php';
}
require 'vendor/autoload.php';

$application = \Net\Bazzline\ClassmapGenerator\Application\Application::create(getcwd());
$application->run();

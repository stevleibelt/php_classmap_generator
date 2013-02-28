<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
namespace Net\Bazzline\ClassmapGenerator;

//make everything relative to the application root
chdir(dirname(__DIR__));
require 'autoloader.php';

Application\Application::create(require 'configuration.php')
    ->andRun();

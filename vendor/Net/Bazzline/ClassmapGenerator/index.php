<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
namespace Net\Bazzline\ClassmapGenerator;

//make everything relative to the application root
chdir(dirname(__DIR__));
require 'Application/ApplicationInterface.php';
require 'Application/Application.php';

Application\Application::create(require 'Configuration/Generator.config.php')
    ->andRun();

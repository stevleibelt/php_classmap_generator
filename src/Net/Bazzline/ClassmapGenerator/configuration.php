<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
return array(
    'createAutoloaderFile' => true,
    'command' => array(
        '\Net\Bazzline\ClassmapGenerator\Command\ManualCommand',
        '\Net\Bazzline\ClassmapGenerator\Command\CreateCommand',
        '\Net\Bazzline\ClassmapGenerator\Command\ConfigtestCommand'
    ),
    'name' => array(
        'classmap' => 'autoloader_classmap.php',
        'autoloader' => 'basicAutoloader.php',
        'projectConfiguration' => 'classmap_generator_configuration.php'
    ),
    'path' => array(
        'whitelist' => array(
            '.' => '*'
        ),
        'blacklist' => array(
            '.' => '*',
            '..' => '*',
            'test' => '*'
        )
    ),
    'defaultTimezone' => 'Europe/Berlin'
);
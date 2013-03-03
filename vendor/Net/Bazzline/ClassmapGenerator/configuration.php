<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
return array(
    'createAutoloaderFile' => false,
    'name' => array(
        'classmap' => 'autoloader_classmap.php',
        'autoloader' => 'autoloader.php',
        'projectConfiguration' => 'classmap_generator_configuration.php'
    ),
    'path' => array(
        'base' => '.',
        'classmap' => '.',
        'autoloader' => '.',
        'whitelist' => array(
            'vendor' => '*'
        ),
        'blacklist' => array(
            '.' => '*',
            '..' => '*',
            'data' => '*',
            '.git' => '*',
            'nbproject' => '*',
            'install' => '*'
        )
    ),
    'defaultTimezone' => 'Europe/Berlin'
);

<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
return array(
    'createAutoloaderFile' => true,
    'name' => array(
        'classmap' => 'autoloader_classmap.php',
        'autoloader' => 'autoloader.php',
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
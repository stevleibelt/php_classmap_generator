<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
return array(
    'createAutoloaderFile' => false,
    'name' => array(
        'classmap' => 'net_bazzline_classmap_generator_autoloader_classmap.php',
        'autoloader' => 'net_bazzline_classmap_generator_autoloader.php',
        'projectConfiguration' => 'classmap_generator_configuration.php'
    ),
    'path' => array(
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
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
        'base' => '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
        'classmap' => '.',
        'projectConfiguration' => '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
        'autloader' => '.',
        'whitelist' => array(
            'vendor' => array(
                'Net' => array(
                    'Bazzline' => array(
                        'ClassmapGenerator' => '*'
                    )
                )
            )
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

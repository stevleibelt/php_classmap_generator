<?php
/**
 * Configuration file for classmap generator
 * @since 2013-04-22 00:09:49
 */

return array (
    'net_bazzline' => array (
        'createAutoloaderFile' => 1,
        'defaultTimezone' => Europe/Berlin,
        'filename' => array (
            'classmap' => 'autoloader_classmap.php',
            'autoloader' => 'generated_autoloader.php'
        ),
        'filepath' => array (
            'classmap' => '',
            'autloader' => '',
            'configuration' => ''
        ),
        'blacklist' => array (
            'tests' => '*'
        ),
        'whitelist' => array (

            'src' => '*'
        )
    )
);
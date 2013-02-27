<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
return array(
    'path' => array(
        'classmap' => 'config/autoloader_classmap.php',
        'whitelist' => array(
            'application',
            'vendor'
        ),
        'blacklist' => array(
            'data',
            '.git',
            'nbproject',
            'install'
        )
    )
);

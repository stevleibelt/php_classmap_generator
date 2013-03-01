<?php
/**
 * @author stev leibelt
 * @since 2013-02-27
 */
return array(
    'path' => array(
        'base' => '.',
        'classmap' => 'autoloader_classmap.php',
//        'classmap' => '/tmp/autoloader_classmap.php',
        'whitelist' => array(
            '../../../application' => array(
                '*'
            ),
            '../../../vendor' => array(
                '*'
            )
        ),
        'blacklist' => array(
            '../../../.' => array(
                '*'
            ),
            '../../../..' => array(
                '*'
            ),
            '../../../data' => array(
                '*'
            ),
            '../../../.git' => array(
                '*'
            ),
            '../../../nbproject' => array(
                '*'
            ),
            '../../../install' => array(
                '*'
            )
        )
    )
);

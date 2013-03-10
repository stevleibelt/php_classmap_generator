<?php
/**
 * @author stev leibelt
 * @since 2013-03-03
 */
return array(
    'net_bazzline' => array(
        'createAutoloaderFile' => true,
        'name' => array(
            'classmap' => 'net_bazzline_classmap_generator_autoloader_classmap.php',
            'autoloader' => 'net_bazzline_classmap_generator_autoloader.php',
        ),
        'path' => array(
            'whitelist' => array(
                //example for adding directory relative to base 
    //            'application' => '*',
                //example for adding directory relative to base with qualified path
    //            'vendor' => array(
    //                'Net' => array(
    //                    'Bazzline' => array(
    //                        'ClassmapGenerator' => '*'
    //                    )
    //                ),
    //            ),
            ),
            'blacklist' => array(
                //example for removing directory relative to base 
    //            '.' => '*',
    //             '..' => '*',
    //            'data' => '*',
    //            '.git' => '*',
    //            'nbproject' => '*',
    //            'install' => '*'
            )
        )
    )
);
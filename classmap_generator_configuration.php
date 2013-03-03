<?php
/**
 * @author stev leibelt
 * @since 2013-03-03
 */
return array(
    'createAutoloaderFile' => true,
    'path' => array(
        'base' => '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..',
        'classmap' => '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..',
        'autoloader' => '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
        'whitelist' => array(
            //example for adding directory relative to base 
            //'application' => '*',
            //example for adding directory relative to base with qualified path
            //'vendor' => array(
            //    'Net' => array(
            //        'Bazzline' => array(
            //            'ClassmapGenerator' => '*'
            //        )
            //    ),
            //),
        )
    )
);
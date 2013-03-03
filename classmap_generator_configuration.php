<?php
/**
 * @author stev leibelt
 * @since 2013-03-03
 */
return array(
    'createAutoloaderFile' => false,
    'path' => array(
        'classmap' => '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..',
        'whitelist' => array(
            'application' => '*',
            'vendor' => array(
                'Net' => array(
                    'Bazzline' => array(
                        'ClassmapGenerator' => '*'
                    )
                ),
            ),
            'application' => '*',
            'classes' => '*',
            'namespaces' => '*',
            'diretory' => array(
                'another' => '*'
            )
        )
    )
);
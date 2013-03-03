<?php
/**
 * @author stev leibelt
 * @since 2013-03-03
 */
return array(
    'createAutoloaderFile' => false,
    'path' => array(
        'whitelist' => array(
            'application' => '*',
            'vendor' => array(
                'Net' => array(
                    'Bazzline' => array(
                        'ClassmapGenerator' => '*'
                    )
                ),
            )
        )
    )
);
<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class HelpCommand extends CommandAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    public function execute()
    {
        $data = array(
            'Invalid argument supplied.',
            '',
            'Manual',
            '==============',
            'NAME',
            '        php - classmap generator for psr-2 based php projects',
            'SYNOPSIS',
            '        index.php [OPTION]',
            'DESCRIPTION',
            '        Creates classmap by iterating over project directories.',
            '',
            '        Following options are available.',
            '',
            '        create',
            '            Creates classmap if no classmap file exists.',
            '        force',
            '            Creates classmap even if classmap file exists.',
            '        configtest',
            '            Tests configuration file.',
            '        help',
            '            Print this manual.',
            'AUTHOR  ',
            '        Written by Stev Leibelt',
            '',
            'REPORTING BUGS',
            '        artodeto@arcor.de',
            '',
            'SEE ALSO',
            '        artodeto.bazzline.net'
        );

        $view = $this->getView();
        $view->setData($data);
        $view->render();
    }
}
<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class HelpCommand extends Command
{
    /**
     * @author stev leibelt
     * @since 2013-04-17
     */
    protected function configure()
    {
        $this
            ->setName('help')
            ->setDescription('Show helpfull informations')
        ;
    }

    /**
     * @author stev leibelt
     * @since 2013-04-17
     */
    protected function execute(InputInterface $input, OutputInterface $output)
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

        foreach ($data as $outputLine) {
            $output->writeln($outputLine);
        }
    }
}
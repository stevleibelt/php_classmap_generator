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
class ManualCommand extends Command
{
    /**
     * @author stev leibelt
     * @since 2013-04-17
     */
    protected function configure()
    {
        $this
            ->setName('manual')
            ->setDescription('Displays manual.')
        ;
    }

    /**
     * @author stev leibelt
     * @since 2013-04-17
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = array(
            '<comment>Invalid argument supplied.</comment>',
            '',
            '<comment>Manual</comment>',
            '==============',
            '<comment>NAME</comment>',
            '        <info>php - classmap generator for psr-2 based php projects</info>',
            '<comment>SYNOPSIS</comment>',
            '        <info>index.php [OPTION]</info>',
            '<comment>DESCRIPTION</comment>',
            '        Creates classmap by iterating over project directories.',
            '',
            '        Following options are available.',
            '',
            '        <info>create</info>',
            '            Creates classmap if no classmap file exists.',
            '        <info>force</info>',
            '            Creates classmap even if classmap file exists.',
            '        <info>configtest</info>',
            '            Tests configuration file.',
            '        <info>help</info>',
            '            Print this manual.',
            '<comment>AUTHOR</comment>',
            '        Written by Stev Leibelt',
            '',
            '<comment>REPORTING BUGS</comment>',
            '        artodeto@arcor.de',
            '',
            '<comment>SEE ALSO</comment>',
            '        artodeto.bazzline.net'
        );

        foreach ($data as $outputLine) {
            $output->writeln($outputLine);
        }
    }
}
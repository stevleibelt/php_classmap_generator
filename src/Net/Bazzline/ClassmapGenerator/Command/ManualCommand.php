<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class ManualCommand extends CommandAbstract
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
            ->setHelp(
                'The <info>%command.name%</info> gives you a rough introduction ' . PHP_EOL .
                    'into available commands for this console application'
            )
        ;
    }

    /**
     * @author stev leibelt
     * @since 2013-04-17
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = array(
            '<comment>Manual</comment>',
            '==============',
            '<comment>NAME</comment>',
            '        universal php classmap generator (especially for non psr-0 projects)',
            '<comment>SYNOPSIS</comment>',
            '        <info>net_bazzline_classmap_generator.php command [OPTION]</info>',
            '<comment>DESCRIPTION</comment>',
            '        Creates classmap by iterating over project directories.',
            '',
            '        Following options are available.',
            '',
            '        <info>create</info>',
            '            Creates classmap if no classmap file exists.',
            '        <info>create --force</info>',
            '        <info>create -f</info>',
            '            Creates classmap even if file exists.',
            '        <info>create --classmap</info>',
            '        <info>create -c</info>',
            '            Creates only classmap file.',
            '        <info>create --autoloader</info>',
            '        <info>create -a</info>',
            '            Creates only autoloader file.',
            '        <info>configure</info>',
            '            Create the configuration file.',
            '        <info>configure --detail</info>',
            '        <info>configure -d</info>',
            '            Create a full configuration file (generally not needed).',
            '        <info>manual</info>',
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
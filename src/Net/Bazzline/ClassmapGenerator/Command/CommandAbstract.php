<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Abstract command class to wrap Symfony\Command
 *  http://symfony.com/doc/master/components/console/introduction.html#displaying-a-progress-bar
 *
 * @author stev leibelt
 * @since 2013-02-27
 */
class CommandAbstract extends Command
{
    /**
     * Uses askAndValidate from Symfony\Console
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $question
     * @param $validator
     * @param bool $numberOfTriesToAsk
     * @param null $default
     * @return mixed
     * @author stev leibelt
     * @since 2013-04-22
     */
    protected function askAndValidate(OutputInterface $output, $question, $validator, $numberOfTriesToAsk = false, $default = null)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->askAndValidate(
            $output,
            $question . PHP_EOL,
            $validator,
            $numberOfTriesToAsk,
            $default
        );
    }
}
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
class ConfigureCommand extends CommandAbstract
{
    private $configuration;
    private $haltOnError;

    /**
     * @author stev leibelt
     * @param boolean $haltOnError
     * @since 2013-02-28
     */
    public function setHaltOnError($haltOnError)
    {
        $this->haltOnError = (boolean) $haltOnError;
    }

    /**
     * @author stev leibelt
     * @param array $configuration
     * @since 2013-02-28
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @author stev leibelt
     * @since 2013-04-21
     */
    protected  function configure()
    {
        $this
            ->setName('configtest')
            ->setDescription('Validates configuration')
        ;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    public function execute() 
    {
        $configurationValidator = new ConfigurationValidate();
        if (!$configurationValidator->isValid($this->configuration)) {
            $view = $this->getView();
            $view->addData('You configuration is not valid.');
            $view->render();

            if ($this->haltOnError) {
                exit (1);
            }
        }
    }
}
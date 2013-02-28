<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationValidator;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class ConfigtestCommand extends CommandAbstract
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
     * @since 2013-02-28
     */
    public function execute() 
    {
        $configurationValidator = new ConfigurationValidator();
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
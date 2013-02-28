<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

use Net\Bazzline\ClassmapGenerator\View\ErrorView;
use Net\Bazzline\ClassmapGenerator\View\HelpView;
use Net\Bazzline\ClassmapGenerator\View\InfoView;
use Net\Bazzline\ClassmapGenerator\Command\ConfigtestCommand;
use Net\Bazzline\ClassmapGenerator\Command\CreateCommand;
use Net\Bazzline\ClassmapGenerator\Command\HelpCommand;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class Application implements ApplicationInterface
{
    const ARGUMENT_CREATE = 'create';
    const ARGUMENT_FORCE = 'force';
    const ARGUMENT_CONFIGTEST = 'configtest';
    const ARGUMENT_HELP = 'help';

    /**
     * @author stev leibelt
     * @since 2013-02-27
     * @var array
     */
    private $configuration;

    /**
     * @author stev leibelt
     * @param array $configuration
     * @since 2013-02-27
     */
    private function __construct(array $configuration = array())
    {
        if (date_default_timezone_get() === false) {
            date_default_timezone_set('Europe/Berlin');
        }
        $this->configuration = $configuration;
    }

    /**
     * @author stev leibelt
     * @param array $configuration
     * @return \Net\Bazzline\ClassmapGenerator\Application\Application
     * @since 2013-02-27
     */
    public static function create(array $configuration = array())
    {
        $application = new self($configuration);

        return $application;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    public function andRun()
    {
        global $argv;
        global $argc;

        $this->validateCliMode();
        $this->validateArguments($argc, $argv);

        switch ($argv[1]) {
            case self::ARGUMENT_CONFIGTEST:
                $this->executeConfigvalidation();
                break;
            case self::ARGUMENT_FORCE:
                $this->executeCreate(true);
                break;
            case self::ARGUMENT_HELP:
                $this->executeHelp();
                break;
            case self::ARGUMENT_CREATE:
            default:
                $this->executeCreate();
                break;
        }

        exit (0);
    }

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    private function executeConfigvalidation($haltOnError = false)
    {
        $command = new ConfigtestCommand();
        if ($haltOnError) {
            $command->setView(new ErrorView());
        } else {
            $command->setView(new InfoView());
        }
        $command->setHaltOnError($haltOnError);
        $command->setConfiguration($this->configuration);
        $command->execute();
    }

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    private function executeCreate($force = false)
    {
        $this->executeConfigvalidation(true);

        $command = new CreateCommand();
        $command->setView(new InfoView());
        $command->setForce($force);
        $command->setBasePath($this->configuration['path']['base']);
        $command->setOutputpath($this->configuration['path']['classmap']);
        $command->setWhitelistDirectories($this->configuration['path']['whitelist']);
        $command->setBlacklistDirectories($this->configuration['path']['blacklist']);
        $command->execute();
    }

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    private function executeHelp()
    {
        $command = new HelpCommand();
        $command->setView(new HelpView());
        $command->execute();
    }

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    private function validateCliMode()
    {
        if (PHP_SAPI !== 'cli') {
            $view = new ErrorView();
            $view->addData('Script has to run in cli mode.');

            $view->render();
            exit (1);
        }
    }

    /**
     * @author stev leibelt
     * @param integer $numberOfArguments
     * @param array $argumentValues
     * @since 2013-02-27
     */
    private function validateArguments($numberOfArguments, $argumentValues)
    {
        $validArguments = array(
            self::ARGUMENT_CONFIGTEST,
            self::ARGUMENT_CREATE,
            self::ARGUMENT_FORCE,
            self::ARGUMENT_HELP
        );

        if (($numberOfArguments < 2)
            || (!in_array($argumentValues[1], $validArguments))) {
            $this->executeHelp();
            exit (1);
        }
    }
}
<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

use Net\Bazzline\ClassmapGenerator\View\ErrorView;
use Net\Bazzline\ClassmapGenerator\View\HelpView;
use Net\Bazzline\ClassmapGenerator\View\InfoView;
use Net\Bazzline\ClassmapGenerator\Command\ConfigtestCommand;
use Net\Bazzline\ClassmapGenerator\Command\CreateCommand;
use Net\Bazzline\ClassmapGenerator\Command\HelpCommand;
use Net\Bazzline\ClassmapGenerator\Validate\CliValidate;
use Net\Bazzline\ClassmapGenerator\Validate\ArgumentValidate;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class Application implements CliApplicationInterface
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
     * @since 2013-03.04
     * @var string
     */
    private $userWorkingDirectory;

    /**
     * @author stev leibelt
     * @param array $configuration
     * @since 2013-02-27
     */
    private function __construct($userWorkingDirectory)
    {
        $this->configuration = require 'configuration.php';
        $this->userWorkingDirectory = $userWorkingDirectory;

        if (date_default_timezone_get() === false) {
            date_default_timezone_set($this->configuration['defaultTimezone']);
        }
    }

    /**
     * @author stev leibelt
     * @param array $configuration
     * @return \Net\Bazzline\ClassmapGenerator\Application\Application
     * @since 2013-02-27
     */
    public static function create($userWorkingDirectory)
    {
        $application = new self($userWorkingDirectory);

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
        $this->mergeConfigurationWithProjectConfigurationIfAvailable();

        switch ($argv[1]) {
            case self::ARGUMENT_CONFIGTEST:
                $this->executeConfigvalidation();
                break;
            case self::ARGUMENT_FORCE:
                $haltOnError = true;
                $forceWriting = true;
                $this->executeConfigvalidation($haltOnError);
                $this->executeCreate($forceWriting);
                break;
            case self::ARGUMENT_HELP:
                $this->executeHelp();
                break;
            case self::ARGUMENT_CREATE:
            default:
                $haltOnError = true;
                $this->executeConfigvalidation($haltOnError);
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
     * @param boolean $forceWriting
     * @since 2013-02-27
     */
    private function executeCreate($forceWriting = false)
    {
        $command = new CreateCommand();
        $command->setView(new InfoView());
        $command->setForce($forceWriting);
        $command->setBasePath($this->configuration['path']['base']);
        $command->setClassmapOutputpath($this->configuration['path']['classmap']);
        $command->setClassmapFilename($this->configuration['name']['classmap']);
        $command->setWhitelistDirectories($this->configuration['path']['whitelist']);
        $command->setBlacklistDirectories($this->configuration['path']['blacklist']);
        $command->setCreateAutloaderFile($this->configuration['createAutoloaderFile']);
        $command->setAutoloaderFilename($this->configuration['name']['autoloader']);
        $command->setAutoloaderOutputpath($this->configuration['path']['autoloader']);
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
        $cliValidate = new CliValidate();
        if (!$cliValidate->isValid()) {
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

        $argumentValidate = new ArgumentValidate();
        $argumentValidate->setData(array());
        if (($numberOfArguments != 2)
            || !$argumentValidate->isValid($validArguments)) {
            $this->executeHelp();
            exit (1);
        }
    }



    /**
     * @author stev leibelt
     * @since 2013-03-02
     */
    private function mergeConfigurationWithProjectConfigurationIfAvailable()
    {
        $isProjectConfigurationAvailable = ((isset($this->configuration['name']))
            && (isset($this->configuration['name']['projectConfiguration']))
            && (is_file($this->configuration['name']['projectConfiguration'])));

        if ($isProjectConfigurationAvailable) {
            $this->configuration = array_replace_recursive($this->configuration, require $this->configuration['name']['projectConfiguration']);
        }
    }
}
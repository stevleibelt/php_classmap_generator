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

use Symfony\Component\Console\Application as SymfonyApplication;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class Application extends SymfonyApplication
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
    public function __construct($userWorkingDirectory)
    {
        parent::__construct('classmap generator', '1.1');
        $this->configuration = require 'configuration.php';
        $this->userWorkingDirectory = $userWorkingDirectory;

        $this->mergeConfigurationWithProjectConfigurationIfAvailable();

        if (date_default_timezone_get() === false) {
            date_default_timezone_set($this->configuration['defaultTimezone']);
        }
        $this->add(new \Net\Bazzline\ClassmapGenerator\Command\HelpCommand());
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
        $this->validateArguments($argv);

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
        $command->setBasePath($this->userWorkingDirectory);
        $command->setClassmapOutputpath($this->userWorkingDirectory);
        $command->setClassmapFilename($this->configuration['name']['classmap']);
        $command->setWhitelistDirectories($this->configuration['path']['whitelist']);
        $command->setBlacklistDirectories($this->configuration['path']['blacklist']);
        $command->setCreateAutloaderFile($this->configuration['createAutoloaderFile']);
        $command->setAutoloaderFilename($this->configuration['name']['autoloader']);
        $command->setAutoloaderOutputpath($this->userWorkingDirectory);
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
     * @param array $argumentValues
     * @since 2013-02-27
     */
    private function validateArguments($argumentValues)
    {
        $data = array(
            ArgumentValidate::DATA_ARGUMENT_VALUES => $argumentValues,
            ArgumentValidate::DATA_VALID_ARGUMENTS => array(
                self::ARGUMENT_CONFIGTEST,
                self::ARGUMENT_CREATE,
                self::ARGUMENT_FORCE,
                self::ARGUMENT_HELP
            )
        );

        $argumentValidate = new ArgumentValidate();
        if (!$argumentValidate->isValid($data)) {
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
            && (is_file($this->userWorkingDirectory . DIRECTORY_SEPARATOR . $this->configuration['name']['projectConfiguration'])));

        if ($isProjectConfigurationAvailable) {
            $projectConfiguration = require $this->userWorkingDirectory . 
                    DIRECTORY_SEPARATOR . 
                    $this->configuration['name']['projectConfiguration'];
            $this->configuration = array_replace_recursive(
                $this->configuration, 
                $projectConfiguration['net_bazzline']
            );
        }
    }
}
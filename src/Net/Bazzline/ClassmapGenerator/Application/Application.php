<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

use Net\Bazzline\ClassmapGenerator\View\ErrorView;
use Net\Bazzline\ClassmapGenerator\View\HelpView;
use Net\Bazzline\ClassmapGenerator\View\InfoView;
use Net\Bazzline\ClassmapGenerator\Command\ConfigtestCommand;
use Net\Bazzline\ClassmapGenerator\Command\CreateCommand;
use Net\Bazzline\ClassmapGenerator\Command\ManualCommand;
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
    const VERSION = '1.1.0.1';
    const NAME = 'classmap generator';

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
        parent::__construct(self::NAME, self::VERSION);
        $this->configuration = require __DIR__ . '/../configuration.php';
        $this->userWorkingDirectory = $userWorkingDirectory;

        $this->mergeConfigurationWithProjectConfigurationIfAvailable();

        if (date_default_timezone_get() === false) {
            date_default_timezone_set($this->configuration['defaultTimezone']);
        }

        foreach ($this->getCommandsFromConfiguration() as $command) {
            $this->add($command);
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

    /**
     * @author stev leibelt
     * @since 2013-04-21
     * @return array
     */
    private function getCommandsFromConfiguration()
    {
        $commands = array();

        foreach ($this->configuration['command'] as $className) {
            if (class_exists($className)) {
                $commands[] = new $className();
            }
        }

        return $commands;
    }
}
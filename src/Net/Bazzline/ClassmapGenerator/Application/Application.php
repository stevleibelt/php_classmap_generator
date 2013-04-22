<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

use Net\Bazzline\ClassmapGenerator\View\ErrorView;
use Net\Bazzline\ClassmapGenerator\View\HelpView;
use Net\Bazzline\ClassmapGenerator\View\InfoView;
use Net\Bazzline\ClassmapGenerator\Command\ConfigureCommand;
use Net\Bazzline\ClassmapGenerator\Command\CreateCommand;
use Net\Bazzline\ClassmapGenerator\Command\ManualCommand;
use Net\Bazzline\ClassmapGenerator\Validate\CliValidate;
use Net\Bazzline\ClassmapGenerator\Validate\ArgumentValidate;

use Symfony\Component\Console\Application as SymfonyApplication;
use RuntimeException;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class Application extends SymfonyApplication
{
    const CONFIGURATION_FILE_NAME = 'classmap_generator_configuration.php';
    const NAME = 'classmap generator';
    const VERSION = '1.1.0.1';

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
        $this->configuration = (file_exists(self::CONFIGURATION_FILE_NAME) ? require self::CONFIGURATION_FILE_NAME : array());
        $this->userWorkingDirectory = $userWorkingDirectory;

        $this->mergeConfigurationWithProjectConfigurationIfAvailable();

        if (date_default_timezone_get() === false) {
            date_default_timezone_set($this->configuration['defaultTimezone']);
        }

        $this->add(new ManualCommand());
        $this->add(new ConfigureCommand());
        $this->add(new CreateCommand());
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
}
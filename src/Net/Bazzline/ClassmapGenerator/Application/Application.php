<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

use Net\Bazzline\ClassmapGenerator\Command\ConfigureCommand;
use Net\Bazzline\ClassmapGenerator\Command\CreateCommand;
use Net\Bazzline\ClassmapGenerator\Command\ManualCommand;
use Net\Bazzline\ClassmapGenerator\Validate\ArgumentValidate;

use Symfony\Component\Console\Application as SymfonyApplication;

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
     * @return array
     * @since 2013-04-22
     */
    public function getConfiguration()
    {
        return $this->configuration['net_bazzline'];
    }
}
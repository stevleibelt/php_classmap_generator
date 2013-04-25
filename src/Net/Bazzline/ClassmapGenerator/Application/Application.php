<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

use Net\Bazzline\ClassmapGenerator\Command\ConfigureCommand;
use Net\Bazzline\ClassmapGenerator\Command\CreateCommand;
use Net\Bazzline\ClassmapGenerator\Command\ManualCommand;
use Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationPhpArray;
use Net\Bazzline\ClassmapGenerator\Filesystem\Filesystem;
use Net\Bazzline\ClassmapGenerator\Validate\ArgumentValidate;

use Symfony\Component\Console\Application as SymfonyApplication;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class Application extends SymfonyApplication
{
    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const CONFIGURATION_FILE_NAME = 'classmap_generator_configuration.php';

    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const NAME = 'classmap generator';

    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const VERSION = '1.4.1.0';

    /**
     * @var \Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationInterface
     *
     * @author stev leibelt
     * @since 2013-02-27
     */
    private $configuration;

    /**
     * @var \Net\Bazzline\ClassmapGenerator\Filesystem\Filesystem
     *
     * @author stev leibelt
     * @since 2013-03-25
     */
    private $filesystem;

    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-03.04
     */
    private $userWorkingDirectory;

    /**
     * Creates application class
     *
     * @param string $userWorkingDirectory - directory from where application is called
     *
     * @author stev leibelt
     * @since 2013-02-27
     */
    public function __construct($userWorkingDirectory)
    {
        parent::__construct(self::NAME, self::VERSION);

        $this->configuration = (file_exists(self::CONFIGURATION_FILE_NAME)
            ? ConfigurationPhpArray::createFromSource(require self::CONFIGURATION_FILE_NAME)
            : new ConfigurationPhpArray());
        $this->filesystem = new Filesystem();
        $this->userWorkingDirectory = $userWorkingDirectory;

        if (date_default_timezone_get() === false) {
            date_default_timezone_set($this->configuration['defaultTimezone']);
        }

        $this->add(new ManualCommand());
        $this->add(new ConfigureCommand());
        $this->add(new CreateCommand());
    }

    /**
     * Factory method for class
     *
     * @param string $userWorkingDirectory - directory from where application is called
     *
     * @return \Net\Bazzline\ClassmapGenerator\Application\Application
     * @author stev leibelt
     * @since 2013-02-27
     */
    public static function create($userWorkingDirectory)
    {
        $application = new self($userWorkingDirectory);

        return $application;
    }

    /**
     * Returns the configuration
     *
     * @return \Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationInterface
     * @author stev leibelt
     * @since 2013-04-22
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Returns the filesystem
     *
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Filesystem
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }
}
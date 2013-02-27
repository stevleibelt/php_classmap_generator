<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class Application implements ApplicationInterface
{
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
//        $configuration = Configuration::createFromArray($configuration);

        return $application;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    public function andRun()
    {
if (PHP_SAPI !== 'cli') {
  echo 'script should only run in commandline mode';
  exit (1);
}

$this->configuration['ignore']['path'][] = $argv[0];
echo '$argv' . PHP_EOL;

echo var_export($argv, true);
    }
}
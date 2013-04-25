<?php
/**
 * @author stev leibelt
 * @since 2013-04-25 
 */

namespace Net\Bazzline\ClassmapGenerator\Configuration;

/**
 * Aware interface for configuration dependency injection
 *
 * @author stev leibelt
 * @since 2013-04-25
 */
interface ConfigurationAwareInterface
{
    /**
     * Getter for configuration dependency injection
     *
     * @return \Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationInterface
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getConfiguration();

    /**
     * Setter for configuration dependency injection
     *
     * @param \Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationInterface $configuration - configuration
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setConfiguration(ConfigurationInterface $configuration);
}
<?php

namespace Net\Bazzline\ClassmapGenerator\Configuration;

/**
 * @author stev leibelt
 * @since 2013-04-25 
 */
interface ConfigurationInterface
{
    /**
     * Creates the configuration from existing source.
     *
     * @param mixed $source - the source data
     * @param string $namespace - the namespace
     *
     * @return \Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationInterface
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public static function createFromSource($source, $namespace = ConfigurationAbstract::DEFAULT_NAMESPACE);

    /**
     * Transform existing configuration to the source.
     *
     * @return mixed
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function toSource();

    /**
     * Sets the flag if a autoloader file should be created.
     *
     * @param boolean $createAutoloaderFile - create the file or not
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setCreateAutoloaderFile($createAutoloaderFile);

    /**
     * Answers the question if a autoloader file should be created.
     *
     * @return boolean
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function createAutoloaderFile();

    /**
     * Returns the default timezone
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getDefaultTimezone();

    /**
     * Sets default timezone
     *
     * @param string $defaultTimezone - the timezone as "Region/City"
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setDefaultTimezone($defaultTimezone);

    /**
     * Sets the name of the autoloader file
     *
     * @param string $filename - name of the file
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setFilenameAutoloader($filename);

    /**
     * Returns the name of the autoloader file
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilenameAutoloader();

    /**
     * Sets the name of the classmap file
     *
     * @param string $filename - name of the file
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setFilenameClassmap($filename);

    /**
     * Returns the name of the classmap file
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilenameClassmap();

    /**
     * Sets the relative filepath to the autoloader
     *
     * @param string $filepath - relative path to the file
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setFilepathAutoloader($filepath);

    /**
     * Returns the relative filepath to the autoloader
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilepathAutoloader();

    /**
     * Sets the relative filepath to the classmap
     *
     * @param string $filepath - relative path to the file
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setFilepathClassmap($filepath);

    /**
     * Returns the relative filepath to the classmap
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilepathClassmap();

    /**
     * Sets  blacklisted directory paths
     *
     * @param array $blacklist - array of directory paths
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setBlacklist(array $blacklist);

    /**
     * Returns blacklisted directory paths
     *
     * @return array
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getBlacklist();

    /**
     * Sets  whitelisted directory paths
     *
     * @param array $whitelist - array of directory paths
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setWhitelist(array $whitelist);

    /**
     * Returns whitelisted directory paths
     *
     * @return array
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getWhitelist();
}
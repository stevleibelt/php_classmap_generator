<?php

namespace Net\Bazzline\ClassmapGenerator\Configuration;

use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-04-25
 */
abstract class ConfigurationAbstract implements ConfigurationInterface
{
    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const DEFAULT_NAMESPACE = 'net_bazzline';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_CREATE_AUTOLOADER_FILE = 'createAutoloaderFile';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_BLACKLIST = 'blacklist';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_DEFAULT_TIMEZONE = 'defaultTimezone';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_FILENAME = 'filename';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_FILENAME_AUTOLOADER = 'autoloader';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_FILENAME_CLASSMAP = 'classmap';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_FILEPATH = 'filepath';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_FILEPATH_AUTOLOADER = 'autoloader';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_FILEPATH_CLASSMAP = 'classmap';

    /**
     * @value string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    const KEY_WHITELIST = 'whitelist';

    /**
     * @var boolean
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $createAutoloaderFile;

    /**
     * @var array
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $blacklist;

    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $defaultTimezone;

    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $filenameAutoloader;

    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $filenameClassmap;

    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $filepathAutoloader;

    /**
     * @var string
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $filepathClassmap;

    /**
     * @var array
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $whitelist;

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
    public function setCreateAutoloaderFile($createAutoloaderFile)
    {
        $this->createAutoloaderFile = (boolean) $createAutoloaderFile;
    }

    /**
     * Answers the question if a autoloader file should be created.
     *
     * @return boolean
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function createAutoloaderFile()
    {
        return (!is_null($this->createAutoloaderFile))
            ? $this->createAutoloaderFile : true;
    }

    /**
     * Returns the default timezone
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getDefaultTimezone()
    {
        return (!is_null($this->defaultTimezone))
            ? $this->defaultTimezone : 'Europe/Berlin';
    }

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
    public function setDefaultTimezone($defaultTimezone)
    {
        if (($defaultTimezone == '')
            || (substr_count($defaultTimezone, '/') != 1)) {
            throw new InvalidArgumentException(
                'No well formed timezone given.'
            );
        }

        $this->defaultTimezone = $defaultTimezone;
    }

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
    public function setFilenameAutoloader($filename)
    {
        if (($filename == '')
            || (strlen($filename) < 1)) {
            throw new InvalidArgumentException(
                'No well formed filename given.'
            );
        }


        $this->filenameAutoloader = ($this->stringEndsWith($filename, '.php'))
            ? $filename : $filename . '.php';
    }

    /**
     * Returns the name of the autoloader file
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilenameAutoloader()
    {
        return (!is_null($this->filenameAutoloader)) ?
            $this->filenameAutoloader : 'generated_autoloader.php';
    }

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
    public function setFilenameClassmap($filename)
    {
        if (($filename == '')
            || (strlen($filename) < 1)) {
            throw new InvalidArgumentException(
                'No well formed filename given.'
            );
        }

        $this->filenameClassmap = ($this->stringEndsWith($filename, '.php'))
            ? $filename : $filename . '.php';
    }

    /**
     * Returns the name of the classmap file
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilenameClassmap()
    {
        return (!is_null($this->filenameClassmap)) ?
            $this->filenameClassmap : 'generated_classmap.php';
    }

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
    public function setFilepathAutoloader($filepath)
    {
        if (($filepath == '')
            || (strlen($filepath) < 1)) {
            throw new InvalidArgumentException(
                'No well formed filepath given.'
            );
        }

        $this->filepathAutoloader = $filepath;
    }

    /**
     * Returns the relative filepath to the autoloader
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilepathAutoloader()
    {
        return (!is_null($this->filepathAutoloader)) ?
            $this->filepathAutoloader : '.';
    }

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
    public function setFilepathClassmap($filepath)
    {
        if (($filepath == '')
            || (strlen($filepath) < 1)) {
            throw new InvalidArgumentException(
                'No well formed filepath given.'
            );
        }

        $this->filepathClassmap = $filepath;
    }

    /**
     * Returns the relative filepath to the classmap
     *
     * @return string
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilepathClassmap()
    {
        return (!is_null($this->filepathClassmap)) ?
            $this->filepathClassmap : '.';
    }

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
    public function setBlacklist(array $blacklist)
    {
        $this->blacklist = $blacklist;
    }

    /**
     * Returns blacklisted directory paths
     *
     * @return array
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getBlacklist()
    {
        return (!is_null($this->blacklist)) ? $this->blacklist : array('.', '..');
    }

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
    public function setWhitelist(array $whitelist)
    {
        $this->whitelist = $whitelist;
    }

    /**
     * Returns whitelisted directory paths
     *
     * @return array
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getWhitelist()
    {
        return (!is_null($this->whitelist)) ? $this->whitelist : array();
    }

    /**
     * Validates if the given string ends with a string.
     *
     * @param string $string - the string to validate the ending
     * @param string $endsWith - the string to validate against
     *
     * @return bool
     * @author stev leibelt
     * @since 2013-04-24
     */
    protected function stringEndsWith($string, $endsWith) {
        $lengthOfEndsWith = strlen($endsWith);
        $stringEnding = substr($string, -$lengthOfEndsWith);

        return ($stringEnding == $endsWith);
    }
}

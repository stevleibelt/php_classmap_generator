<?php

namespace Net\Bazzline\ClassmapGenerator\Configuration;

use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-04-25
 */
class ConfigurationPhpArray extends ConfigurationAbstract
{
    /**
     * Creates the configuration from existing source.
     *
     * @param array $source - the source data
     * @param string $namespace - the namespace
     *
     * @return \Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationInterface
     *
     * @throws \InvalidArgumentException;
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public static function createFromSource($source, $namespace = ConfigurationAbstract::DEFAULT_NAMESPACE)
    {
        if ((!is_array($source))
            || (!isset($source[$namespace]))
            || (count($source[$namespace]) < 1)) {
            throw new InvalidArgumentException(
                'Invalid source given.'
            );
        }

        $array = $source[$namespace];

        $configurationPhpArray = new self();
        $configurationPhpArray->setBlacklist($array[ConfigurationAbstract::KEY_BLACKLIST]);
        $configurationPhpArray->setCreateAutoloaderFile($array[ConfigurationAbstract::KEY_CREATE_AUTOLOADER_FILE]);
        $configurationPhpArray->setDefaultTimezone($array[ConfigurationAbstract::KEY_DEFAULT_TIMEZONE]);
        $configurationPhpArray->setFilenameAutoloader($array[ConfigurationAbstract::KEY_FILENAME][ConfigurationAbstract::KEY_FILENAME_AUTOLOADER]);
        $configurationPhpArray->setFilenameClassmap($array[ConfigurationAbstract::KEY_FILENAME][ConfigurationAbstract::KEY_FILENAME_CLASSMAP]);
        $configurationPhpArray->setFilepathAutoloader($array[ConfigurationAbstract::KEY_FILEPATH][ConfigurationAbstract::KEY_FILEPATH_AUTOLOADER]);
        $configurationPhpArray->setFilepathClassmap($array[ConfigurationAbstract::KEY_FILEPATH][ConfigurationAbstract::KEY_FILEPATH_CLASSMAP]);
        $configurationPhpArray->setWhitelist($array[ConfigurationAbstract::KEY_WHITELIST]);

        return $configurationPhpArray;
    }

    /**
     * Transform existing configuration to the source.
     *
     * @return array
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function toSource()
    {
        $array = array(
            ConfigurationAbstract::KEY_CREATE_AUTOLOADER_FILE => $this->createAutoloaderFile(),
            ConfigurationAbstract::KEY_DEFAULT_TIMEZONE => $this->getDefaultTimezone(),
            ConfigurationAbstract::KEY_FILENAME => array(
                ConfigurationAbstract::KEY_FILENAME_AUTOLOADER => $this->getFilenameAutoloader(),
                ConfigurationAbstract::KEY_FILENAME_CLASSMAP => $this->getFilenameClassmap()
            ),
            ConfigurationAbstract::KEY_FILEPATH => array(
                ConfigurationAbstract::KEY_FILEPATH_AUTOLOADER => $this->getFilepathAutoloader(),
                ConfigurationAbstract::KEY_FILEPATH_CLASSMAP => $this->getFilepathClassmap()
            ),
            ConfigurationAbstract::KEY_BLACKLIST => $this->getBlacklist(),
            ConfigurationAbstract::KEY_WHITELIST => $this->getWhitelist()
        );

        return $array;
    }
}
<?php

namespace Net\Bazzline\ClassmapGenerator\Configuration;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class ConfigurationValidator
{
    /**
     * @author stev leibelt
     * @param array $configuration
     * @return boolean
     * @since 2013-02-27
     */
    public function isValid(array $configuration)
    {
        $isValid = false;

        if ((isset($configuration['path']))
            && (isset($configuration['path']['base']))
            && (isset($configuration['path']['classmap']))
            && (isset($configuration['path']['whitelist']))
            && (isset($configuration['path']['blacklist']))
            && (strlen($configuration['path']['classmap']) > 0)
            && (is_array($configuration['path']['whitelist']))
            && (count($configuration['path']['whitelist']) > 0)
            && (is_array($configuration['path']['blacklist']))
            & (count($configuration['path']['blacklist']) > 0)) {
            $isValid = true;
        }

        return $isValid;
    }
}

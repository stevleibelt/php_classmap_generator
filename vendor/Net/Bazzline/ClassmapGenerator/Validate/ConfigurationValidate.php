<?php

namespace Net\Bazzline\ClassmapGenerator\Validate;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class ConfigurationValidate implements ValidateInterface
{
    /**
     * @author stev leibelt
     * @param mixed $data
     * @return boolean
     * @since 2013-02-27
     */
    public function isValid($data = null)
    {
        $isValid = false;

        if ((is_array($data))
            && (isset($data['createAutoloaderFile']))
            && (isset($data['defaultTimezone']))
            && (isset($data['name']))
            && (isset($data['name']['classmap']))
            && (isset($data['name']['autoloader']))
            && (isset($data['path']))
            && (isset($data['path']['base']))
            && (isset($data['path']['classmap']))
            && (isset($data['path']['autloader']))
            && (isset($data['path']['whitelist']))
            && (isset($data['path']['blacklist']))
            && (strlen($data['path']['classmap']) > 0)
            && (is_array($data['path']['whitelist']))
            && (count($data['path']['whitelist']) > 0)
            && (is_array($data['path']['blacklist']))
            & (count($data['path']['blacklist']) > 0)) {
            $isValid = true;
        }

        return $isValid;
    }
}

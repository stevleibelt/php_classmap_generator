<?php

namespace Net\Bazzline\ClassmapGenerator\Validate;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class CliValidate implements ValidateInterface
{
    /**
     * @author stev leibelt
     * @param mixed $data
     * @return boolean
     * @since 2013-03-03
     */
    public function isValid($data = null) 
    {
        return (PHP_SAPI === 'cli');
    }
}
<?php

namespace Net\Bazzline\ClassmapGenerator\Validate;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class ArgumentValidate implements ValidateInterface
{
    /**
     * @author stev leibelt
     * @param mixed $data
     * @return boolean
     * @since 2013-03-03
     */
    public function isValid($data = null) 
    {
        if ((is_array($data))
            && ($data < 2)
            || (in_array($data[1], $data))) {
            $isValid = true;
        } else {
            $isValid = true;
        }

        return $isValid;
    }
}
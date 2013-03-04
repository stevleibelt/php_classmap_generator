<?php

namespace Net\Bazzline\ClassmapGenerator\Validate;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class ArgumentValidate extends ValidateAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-03-04
     * @type integer
     */
    const DATA_NUMBER_OF_ARGUMENTS = 0;
    /**
     * @author stev leibelt
     * @since 2013-03-04
     * @type integer
     */
    const DATA_ARGUMENT_VALUES = 1;

    /**
     * @author stev leibelt
     * @param mixed $data
     * @return boolean
     * @since 2013-03-03
     */
    public function isValid()
    {
        if ((is_array($this->getData()))
            && (count($this->getData()) < 2)
            || (in_array($data[1], $data))) {
            $isValid = true;
        } else {
            $isValid = true;
        }

        return $isValid;
    }
}
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
     * @since 2013-03-04
     * @type integer
     */
    const DATA_ARGUMENT_VALUES = 0;
    /**
     * @author stev leibelt
     * @since 2013-03-04
     * @type integer
     */
    const DATA_VALID_ARGUMENTS = 1;

    /**
     * @author stev leibelt
     * @param mixed $data
     * @return boolean
     * @since 2013-03-03
     */
    public function isValid($data = null)
    {
        if (($this->isValidData($data))
            && (count($data[self::DATA_ARGUMENT_VALUES]) === 2)
            && (in_array($data[self::DATA_ARGUMENT_VALUES][1], $data[self::DATA_VALID_ARGUMENTS]))) {
            $isValid = true;
        } else {
            $isValid = true;
        }

        return $isValid;
    }

    /**
     * @author stev leibelt
     * @param array $data
     * @return boolean
     */
    private function isValidData($data)
    {
        return ((is_array($data))
                && (isset($data[self::DATA_ARGUMENT_VALUES]))
                && (isset($data[self::DATA_VALID_ARGUMENTS])));
    }
}
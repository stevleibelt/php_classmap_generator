<?php

namespace Net\Bazzline\ClassmapGenerator\Validate;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
interface ValidateInterface
{
    /**
     * @author stev leibelt
     * @param array $data
     * @since 2013-03-04
     */
    public function setData(array $data);

    /**
     * @author stev leibelt
     * @param mixed $value
     * @since 2013-03-04
     */
    public function setValue($value);

    /**
     * @author stev leibelt
     * @return boolean
     * @since 2013-03-03
     */
    public function isValid();
}
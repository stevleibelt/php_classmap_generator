<?php

namespace Net\Bazzline\ClassmapGenerator\Validate;

/**
 * @author stev leibelt
 * @since 2013-03-04
 */
abstract class ValidateAbstract implements ValidateInterface
{
    /**
     * @author stev leibelt
     * @since 2013-03-04
     * @var array 
     */
    private $data;
    /**
     * @author stev leibelt
     * @since 2013-03-04
     * @var mixed 
     */
    private $value;

    /**
     * @author stev leibelt
     * @param array $data
     * @since 2013-03-04
     */
    public function setData(array $data) 
    {
        $this->data = $data;
    }

    /**
     * @author stev leibelt
     * @param mixed $value
     * @since 2013-03-04
     */
    public function setValue($value) 
    {
        $this->value = $value;
    }

    /**
     * @author stev leibelt
     * @return array $data
     * @since 2013-03-04
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * @author stev leibelt
     * @return mixed $value
     * @since 2013-03-04
     */
    protected function getValue()
    {
        return $this->value;
    }
}
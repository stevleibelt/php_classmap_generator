<?php

namespace Net\Bazzline\ClassmapGenerator\View;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
abstract class ViewAbstract
{
    private $data;

    /**
     * @author stev leibelt
     * @param array $data
     * @since 2013-02-27
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @author stev leibelt
     * @param string $data
     * @since 2013-02-27
     */
    public function addData($data)
    {
        if (is_null($this->data)) {
            $this->setData(array());
        }

        $this->data[] = $data;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    abstract public function render();

    /**
     * @author stev leibelt
     * @return array
     * @since 2013-02-27
     */
    protected function getData()
    {
        return (array) $this->data;
    }
}
<?php

namespace Net\Bazzline\ClassmapGenerator\View;

interface RenderInterface
{
    /**
     * @author stev leibelt
     * @param string $data
     * @since 2013-02-27
     */
    public function addData($data);
    
    /**
     * @author stev leibelt
     * @param array $data
     * @since 2013-02-27
     */
    public function setData(array $data);

    /**
     * @author stev leibelt
     * @since 2013-03-03
     */
    public function render();
}
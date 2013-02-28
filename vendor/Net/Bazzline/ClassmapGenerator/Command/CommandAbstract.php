<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Net\Bazzline\ClassmapGenerator\View\ViewAbstract;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
abstract class CommandAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var \Net\Bazzline\ClassmapGenerator\View\ViewAbstract 
     */
    private $view;

    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    public function setView(ViewAbstract $view)
    {
        $this->view = $view;
    }

    /**
     * @author stev leibelt
     * @return \Net\Bazzline\ClassmapGenerator\View\ViewAbstract
     * @since 2013-02-28
     */
    protected function getView()
    {
        return $this->view;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    abstract function execute();
}
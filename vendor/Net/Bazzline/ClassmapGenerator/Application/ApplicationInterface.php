<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
interface ApplicationInterface
{
    /**
     * @author stev leibelt
     * @return Net\Bazzline\ClassmapGenerator\Application\Application
     * @since 2013-02-27
     */
    public static function create();

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    public function andRun();
}
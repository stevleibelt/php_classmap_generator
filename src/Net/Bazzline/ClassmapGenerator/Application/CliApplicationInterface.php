<?php

namespace Net\Bazzline\ClassmapGenerator\Application;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
interface CliApplicationInterface
{
    /**
     * @author stev leibelt
     * @param string $userWorkingDirectory
     * @return Net\Bazzline\ClassmapGenerator\Application\Application
     * @since 2013-02-27
     */
    public static function create($userWorkingDirectory);

    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    public function andRun();
}
<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Analyze;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
interface AnalyzeInterface
{
    /**
     * @author stev leibelt
     * @return string $basepath
     * @since 2013-03-03
     */
    public function getBasepath();

    /**
     * @author stev leibelt
     * @param string $basepath
     * @since 2013-03-03
     */
    public function setBasepath($basepath);

    /**
     * @author stev leibelt
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function analyze($filepath);
}
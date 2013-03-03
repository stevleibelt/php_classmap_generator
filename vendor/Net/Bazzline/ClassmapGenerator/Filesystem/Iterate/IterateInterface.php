<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Iterate;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
interface IterateInterface
{
    /**
     * @author stev leibelt
     * @param string $path
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public function addWhitelistedPath($whitelistedPath);

    /**
     * @author stev leibelt
     * @param string $path
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public function addBlacklistedPath($blacklistedPath);
    /**
     * @author stev leibelt
     * @param string $basepath
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public function setBasepath($basepath);

    /**
     * @author stev leibelt
     * @since 2013-03-03
     * @throws \RuntimeException
     */
    public function iterate();
}
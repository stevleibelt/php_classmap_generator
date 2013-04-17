<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Write;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
interface WriterInterface
{
    /**
     * @author stev leibelt
     * @param mixed $data
     * @since 2013-03-03
     */
    public function setFiledata(array $data);

    /**
     * @author stev leibelt
     * @param string $path
     * @since 2013-03-03
     */
    public function setFilePath($path);

    /**
     * @author stev leibelt
     * @since 2013-03-03
     * @return boolean
     * @throws \RuntimeException
     */
    public function write();

    /**
     * @author stev leibelt
     * @return boolean
     * @since 2013-02-28
     * @throws \RuntimeException
     */
    public function overwrite();

    /**
     * @author stev leibelt
     * @return boolean
     * @since 2013-02-28
     */
    public function fileExists();
}
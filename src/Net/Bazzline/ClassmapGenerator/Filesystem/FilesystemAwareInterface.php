<?php
/**
 * @author stev leibelt
 * @since 2013-04-25 
 */

namespace Net\Bazzline\ClassmapGenerator\Filesystem;

/**
 * Aware interface for filesystem dependency injection
 *
 * @author stev leibelt
 * @since 2013-04-25
 */
interface FilesystemAwareInterface
{
    /**
     * Getter for filesystem dependency injection
     *
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Filesystem $filesystem - filesystem
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilesystem();

    /**
     * Setter for filesystem dependency injection
     *
     * @param \Net\Bazzline\ClassmapGenerator\Filesystem\Filesystem $filesystem - filesystem
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setFilesystem(Filesystem $filesystem);
}
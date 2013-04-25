<?php
/**
 * @author stev leibelt
 * @since 2013-03-03
 */

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Write;

use Net\Bazzline\ClassmapGenerator\Filesystem\FilesystemAwareInterface;
use Net\Bazzline\ClassmapGenerator\Filesystem\Filesystem;

abstract class FilewriterAbstract implements WriterInterface, FilesystemAwareInterface
{
    /**
     * @var array
     * @author stev leibelt
     * @since 2013-03-03
     */
    private $filedata;

    /**
     * @var string
     * @author stev leibelt
     * @since 2013-03-03
     */
    private $filepath;

    private $filesystem;

    /**
     * Setter for file path
     *
     * @param string $filepath
     *
     * @author stev leibelt
     * @since 2013-03-03
     */
    public function setFilePath($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Setter for file data
     *
     * @param array $filedata
     *
     * @author stev leibelt
     * @since 2013-03-03
     */
    public function setFiledata(array $filedata)
    {
        $this->filedata = $filedata;
    }

    /**
     * Validator if file exists
     *
     * @since 2013-03-03
     * @author stev leibelt
     * @return boolean
     */
    public function fileExists()
    {
        return file_exists($this->filepath);
    }

    /**
     * Writes content, even if file exists
     *
     * @return boolean
     * @throws \RuntimeException
     * @author stev leibelt
     * @since 2013-03-03
     */
    public function overwrite()
    {
        if ($this->fileExists()) {
            unlink($this->filepath);
        }

        return $this->write();
    }

    /**
     * Getter for filesystem
     *
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Filesystem $filesystem
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Setter for filesystem
     *
     * @param \Net\Bazzline\ClassmapGenerator\Filesystem\Filesystem $filesystem
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Getter for file path
     *
     * @return string
     * @author stev leibelt
     * @since 2013-03-03
     */
    protected function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Getter for file data
     *
     * @return array
     * @author stev leibelt
     * @since 2013-03-03
     */
    protected function getFiledata()
    {
        return $this->filedata;
    }
}
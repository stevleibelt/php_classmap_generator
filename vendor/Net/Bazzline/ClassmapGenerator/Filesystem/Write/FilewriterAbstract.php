<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Write;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
abstract class FilewriterAbstract implements WriterInterface
{
    /**
     * @author stev leibelt
     * @since 2013-03-03
     * @var array
     */
    private $filedata;

    /**
     * @author stev leibelt
     * @since 2013-03-03
     * @var string
     */
    private $filepath;

    /**
     * @author stev leibelt
     * @param string $filepath
     * @since 2013-03-03
     */
    public function setFilePath($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * @author stev leibelt
     * @param array $filedata
     * @since 2013-03-03
     */
    public function setFiledata(array $filedata)
    {
        $this->filedata = $filedata;
    }

    /**
     * @author stev leibelt
     * @return boolean
     * @since 2013-03-03
     */
    public function fileExists()
    {
        return file_exists($this->filepath);
    }

    /**
     * @author stev leibelt
     * @return boolean
     * @since 2013-03-03
     * @throws \RuntimeException
     */
    public function overwrite()
    {
        if ($this->fileExists()) {
            unlink($this->filepath);
        }

        return $this->write();
    }

    /**
     * @author stev leibelt
     * @return string
     * @since 2013-03-03
     */
    protected function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @author stev leibelt
     * @return array
     * @since 2013-03-03
     */
    protected function getFiledata()
    {
        return $this->filedata;
    }
}
<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem;

use DirectoryIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\PhpFileOrDirectoryFilterIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\FileAnalyzer;
use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-03-01
 */
class FilepathIterator
{
    /**
     * @author stev leibelt
     * @since 2013-03-01
     * @var string
     */
    private $basepath;

    /**
     * @author stev leibelt
     * @since 2013-03-01
     * @var array
     */
    private $blacklistedDirectories;

    /**
     * @author stev leibelt
     * @since 2013-03-01
     * @var array
     */
    private $paths;

    /**
     * @author stev leibelt
     * @param string $basepath
     * @since 2013-03-01
     */
    public function setBasepath($basepath)
    {
        $this->basepath = (string) $basepath;
    }

    /**
     * @author stev leibelt
     * @param array $path
     * @since 2013-03-01
     */
    public function addPath($path)
    {
        if (is_dir($path)) {
            $this->paths[] = $path;
        }
    }

    /**
     * @author stev leibelt
     * @return array
     * @since 2013-02-28
     */
    public function iterate()
    {
        $classmapFileContent = array();
        if (is_null($this->basepath)) {
            $this->setBasepath('');
        }

        foreach ($this->paths as $path) {
            $classmapFileContent += $this->iteratePath($path, $this->basepath);
        }

        return $classmapFileContent;
    }

    /**
     * @author stev leibelt
     * @param array $blacklistedDirectories
     * @since 2013-03-01
     */
    public function setBlacklistedDirectories(array $blacklistedDirectories)
    {
        $this->blacklistedDirectories = $blacklistedDirectories;
    }

    /**
     * @author stev leibelt
     * @param string $path
     * @param string $basepath
     * @return array
     * @since 2013-02-28
     */
    private function iteratePath($path, $basepath)
    {
        $data = array();

        if (is_dir($path)) {
            $fileAnalyzer = new FileAnalyzer();

            $directoryIterator = new PhpFileOrDirectoryFilterIterator(new DirectoryIterator($path));
            $directoryIterator->setDirectoryNamesToFilterOut($this->blacklistedDirectories);

            foreach ($directoryIterator as $entry) {
                if ($entry->isDir()) {
                    $data = array_merge($data, $this->iteratePath($path . DIRECTORY_SEPARATOR . $entry->getFilename(), $basepath));
                } else {
                    try {
                    $data = array_merge($data, $fileAnalyzer->getClassname($path . DIRECTORY_SEPARATOR . $entry->getFilename(), $basepath));
                    } catch (InvalidArgumentException $exception) {
                        echo 'error::' . $exception->getMessage();
                    }
                }
            }
        }

        return $data;
    }
}
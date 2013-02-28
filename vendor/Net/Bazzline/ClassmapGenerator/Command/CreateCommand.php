<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use DirectoryIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\PhpFileOrDirectoryFilterIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\FileAnalyzer;
use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class CreateCommand extends CommandAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var boolean
     */
    private $force;

    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var string 
     */
    private $basePath;

    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var string 
     */
    private $outputPath;

    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var array 
     */
    private $whitelistedDirectories;

    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var array 
     */
    private $blacklistedDirectories;

    /**
     * @authors stev leibelt
     * @since 2013-02-28
     * @var array 
     */
    private $classMapContent;

    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    public function __construct()
    {
        $this->setForce(false);
        $this->setBasePath(getcwd());
        $this->setOutputpath(getcwd());
        $this->setBlacklistDirectories(array());
        $this->setWhitelistDirectories(array());
        $this->classMapContent = array();
    }

    /**
     * @author stev leibelt
     * @param boolean $force
     * @since 2013-02-28
     */
    public function setForce($force)
    {
        $this->force = (boolean) $force;
    }

    /**
     * @author stev leibelt
     * @param string $basePath
     * @since 2013-02-28
     */
    public function setBasePath($basePath)
    {
        $this->basePath = (string) $basePath;
    }

    /**
     * @author stev leibelt
     * @param string $outputPath
     * @since 2013-02-28
     */
    public function setOutputpath($outputPath)
    {
        $this->outputPath = (string) $outputPath;
    }

    /**
     * @author stev leibelt
     * @param array $blacklistDirectories
     * @since 2013-02-28
     */
    public function setBlacklistDirectories(array $blacklistDirectories)
    {
        $this->blacklistedDirectories = $blacklistDirectories;
    }

    /**
     * @author stev leibelt
     * @param array $whilelistDirectories
     * @since 2013-02-28
     */
    public function setWhitelistDirectories(array $whilelistDirectories)
    {
        $this->whitelistedDirectories = $whilelistDirectories;
    }

    /**
     * @author stev leibelt
     * @return boolean $force
     * @since 2013-02-28
     */
    private function isForced()
    {
        return ($this->force === true);
    }

    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @todo implement
     */
    public function execute()
    {
        //create directory iterator
        //load paths to search in
        //create fileView
        //create outputView
        //add fileAnalyzer
        $data = array();
        $data[] = 'Overwrite if file exists (yes/no): ' . ($this->isForced() ? 'yes' : 'no');
        $data[] = '';

        foreach ($this->whitelistedDirectories as $name => $path) {
            if (implode(', ', $path) === '*') {
                $pathToIterateOn = $this->basePath . DIRECTORY_SEPARATOR . $name;

                $data = array_merge($data, $this->iterateDirectory($pathToIterateOn));
            }
        }

        $view = $this->getView();
        $view->setData($data);

        $view->addData('');
        foreach ($this->blacklistedDirectories as $directory => $directoryPath) {
            $view->addData('Ignored directory: ' . $this->basePath . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $directoryPath);
        }
        $view->addData('');
        $view->addData('done');
        $view->render();
    }

    private function iterateDirectory($path)
    {
        $data = array();

        if (is_dir($path)) {
            $directoryIterator = new PhpFileOrDirectoryFilterIterator(new DirectoryIterator($path));
            $directoryIterator->setDirectoryNamesToFilterOut($this->blacklistedDirectories);
            $fileAnalyzer = new FileAnalyzer();

            foreach ($directoryIterator as $entry) {
                $data[] = 'entry:: ' . $entry->getPathname();
                $data[] = 'entry:: ' . $entry->getFilename();
                if ($entry->isDir()) {
                    $data[] = 'entry is directory and on whilelist';
                    $data = array_merge($data, $this->iterateDirectory($path . DIRECTORY_SEPARATOR . $entry->getFilename()));
                } else {
                    $data[] = 'entry is a file';

                    try {
                    $data = array_merge($data, $fileAnalyzer->getClassname($path . DIRECTORY_SEPARATOR . $entry->getFilename()));
                    } catch (InvalidArgumentException $exception) {
                        echo 'error::' . $exception->getMessage();
                    }
                }
            }
        }

        return $data;
    }
}
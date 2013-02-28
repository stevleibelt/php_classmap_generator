<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use DirectoryIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\PhpFileOrDirectoryFilterIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\FileAnalyzer;
use Net\Bazzline\ClassmapGenerator\View\ClassmapFileView;
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
        $viewData = array();
        $viewData[] = 'Overwrite if file exists (yes/no): ' . ($this->isForced() ? 'yes' : 'no');
        $viewData[] = '';

        foreach ($this->whitelistedDirectories as $directory => $directoryPaths) {
            foreach ($directoryPaths as $directoryPath) {
                if ($directoryPath === '*') {
                    $pathToIterateOn = $this->basePath . DIRECTORY_SEPARATOR . $directory;
                } else {
                    $pathToIterateOn = $this->basePath . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $directoryPath;
                }
                $classmapFileContent = $this->iterateDirectory($pathToIterateOn);
            }
        }

        $view = $this->getView();
        $view->setData($viewData);

        $view->addData('');
        foreach ($this->blacklistedDirectories as $directory => $directoryPaths) {
            foreach ($directoryPaths as $directoryPath) {
                $view->addData('Ignored directory: ' . $this->basePath . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $directoryPath);
            }
        }
        $view->addData('');
        $view->addData('done');
        $view->render();

        $classmapView = new ClassmapFileView();
        $classmapView->setClassmapFilepath(dirname(__DIR__) . DIRECTORY_SEPARATOR . $this->outputPath);
        $classmapView->setData($classmapFileContent);
        $classmapView->render();
    }

    private function iterateDirectory($path)
    {
        $data = array();

        if (is_dir($path)) {
            $directoryIterator = new PhpFileOrDirectoryFilterIterator(new DirectoryIterator($path));
            $directoryIterator->setDirectoryNamesToFilterOut($this->blacklistedDirectories);
            $fileAnalyzer = new FileAnalyzer();

            foreach ($directoryIterator as $entry) {
                if ($entry->isDir()) {
                    $data = array_merge($data, $this->iterateDirectory($path . DIRECTORY_SEPARATOR . $entry->getFilename()));
                } else {
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
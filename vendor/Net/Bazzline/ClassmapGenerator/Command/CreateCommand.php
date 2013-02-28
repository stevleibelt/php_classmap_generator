<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Net\Bazzline\ClassmapGenerator\Filesystem\ClassmapFilewriter;
use Net\Bazzline\ClassmapGenerator\Filesystem\FilepathIterator;

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
        $view = $this->getView();
        $view->addData('Overwrite if file exists (yes/no): ' . ($this->isForced() ? 'yes' : 'no'));
        $view->addData('');

        $filepathIterator = new FilepathIterator();
        $filepathIterator->setBlacklistedDirectories($this->blacklistedDirectories);

        foreach ($this->whitelistedDirectories as $directory => $directoryPaths) {
            foreach ($directoryPaths as $directoryPath) {
                if ($directoryPath === '*') {
                    $filepathIterator->addPath(
                        $this->basePath . DIRECTORY_SEPARATOR .
                        $directory
                    );
                } else {
                    $filepathIterator->addPath(
                        $this->basePath . DIRECTORY_SEPARATOR .
                        $directory . DIRECTORY_SEPARATOR .
                        $directoryPath
                    );
                }
            }
        }

        $classmapFileContent = $filepathIterator->iterate();

        $classmapFileWriter = new ClassmapFilewriter();
        $classmapFileWriter->setClassmapFilepath(dirname(__DIR__) . DIRECTORY_SEPARATOR . $this->outputPath);
        $classmapFileWriter->setFiledata($classmapFileContent);
        if ($classmapFileWriter->fileExists()) {
            if ($this->isForced()) {
                $view->addData(($classmapFileWriter->overwrite()) ? 'classmap written' : 'classmap was not written');
            } else {
                $view->addData('Classmapfile exists. Use force to overwrite.');
            }
        } else {
            $view->addData(($classmapFileWriter->write()) ? 'classmap written' : 'classmap was not written');
        }

        $view->addData('');
        $view->addData('done');
        $view->render();
    }
}
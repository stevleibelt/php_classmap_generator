<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use DirectoryIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\PhpFileOrDirectoryFilterIterator;

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
        $view = $this->getView();
        $view->setData($data);
        $view->addData('Overwrite if file exists (yes/no): ' . ($this->isForced() ? 'yes' : 'no'));
        $view->addData('');

        $directoryIterator = new PhpFileOrDirectoryFilterIterator(new DirectoryIterator($this->basePath));
        $directoryIterator->setDirectoryNamesToFilter($this->blacklistedDirectories);
        foreach ($directoryIterator as $entry) {
            $view->addData('entry:: ' . $entry->getPathname());
            $view->addData('entry:: ' . $entry->isDir());
            $view->addData('entry:: ' . $entry->getFilename());
            if ($entry->isDir()
                && (in_array($entry->getFilename(), $this->whitelistedDirectories))) {
                $view->addData('entry is directory and on whilelist');
            }
        }

        $view->addData('');
        foreach ($this->blacklistedDirectories as $directory) {
            $view->addData('Ignored directory: ' . $this->basePath . DIRECTORY_SEPARATOR . $directory);
        }
        $view->addData('');
        $view->addData('done');
        $view->render();
    }
}
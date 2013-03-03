<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Net\Bazzline\ClassmapGenerator\Factory\FilepathIteratorFactory;
use Net\Bazzline\ClassmapGenerator\Factory\ClassmapFilewriterFactory;

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
     * @since 2013-03-03
     * @var string 
     */
    private $classMapFileName;

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
     * @param string $filename
     * @since 2013-03-03
     */
    public function setFilename($filename)
    {
        $this->classMapFileName = (string) $filename;
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

        $filepathIterator = FilepathIteratorFactory::create(
            array(
                FilepathIteratorFactory::OPTION_BASE_PATH => $this->basePath,
                FilepathIteratorFactory::OPTION_BLACKISTED_DIRECTORIES => $this->generatePaths($this->blacklistedDirectories),
                FilepathIteratorFactory::OPTION_WHITELISTED_DIRECTORIES => $this->generatePaths($this->whitelistedDirectories)
            )
        );

        $classmapFileWriter = ClassmapFilewriterFactory::create(
            array(
                ClassmapFilewriterFactory::OPTION_FILE_DATA => $filepathIterator->iterate(),
                ClassmapFilewriterFactory::OPTION_FILE_PATH => realpath($this->outputPath) . DIRECTORY_SEPARATOR . $this->classMapFileName
            )
        );
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
    
    
    
    /**
     * @author stev leibelt
     * @param array $paths
     * @param string $parentDirectory
     * @return array
     * @since 2013-03-02
     */
    private function generatePaths($paths, $parentDirectory = '')
    {
        $generatedPaths = array();

        if ($parentDirectory !== '') {
            $parentDirectory .= DIRECTORY_SEPARATOR;
        }

        foreach ($paths as $currentPath => $belowPath) {
            $currentWorkingPath = $parentDirectory . $currentPath;
            if (is_array($belowPath)) {
                $generatedPaths = array_merge($generatedPaths, $this->generatePaths($belowPath, $currentWorkingPath));
            } else if ($belowPath !== '*') {
                $generatedPaths[] = $currentWorkingPath . DIRECTORY_SEPARATOR . $belowPath;
            } else {
                $generatedPaths[] = $currentWorkingPath;
            }
        }

        return $generatedPaths;
    }
}
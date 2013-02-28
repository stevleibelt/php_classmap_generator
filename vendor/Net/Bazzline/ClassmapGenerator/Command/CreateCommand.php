<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use DirectoryIterator;
use ReflectionClass;
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

        $view = $this->iterateDirectory($this->basePath, $view);

        $view->addData('');
        foreach ($this->blacklistedDirectories as $directory) {
            $view->addData('Ignored directory: ' . $this->basePath . DIRECTORY_SEPARATOR . $directory);
        }
        $view->addData('');
        $view->addData('done');
        $view->render();
    }

    private function iterateDirectory($path, $view)
    {
        $directoryIterator = new PhpFileOrDirectoryFilterIterator(new DirectoryIterator($path));
        $directoryIterator->setDirectoryNamesToFilterOut($this->blacklistedDirectories);
        foreach ($directoryIterator as $entry) {
            $view->addData('entry:: ' . $entry->getPathname());
            $view->addData('entry:: ' . $entry->getFilename());
//            if ($entry->isDir()
//                && (in_array($entry->getFilename(), $this->whitelistedDirectories))) {
            if ($entry->isDir()) {
                $view->addData('entry is directory and on whilelist');
                $this->iterateDirectory($path . DIRECTORY_SEPARATOR . $entry->getFilename(), $view);
            } else {
                $view->addData('entry is file');

                //http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
                //http://wiki.birth-online.de/snippets/php/get-classes-in-file
                $fileContent = file_get_contents($path . DIRECTORY_SEPARATOR . $entry->getFilename());
                $tokens = token_get_all($fileContent);
                $class_token = false;
                foreach ($tokens as $token) {
                    if ( !is_array($token) ) continue;
                    if (($token[0] == T_CLASS)
                        || ($token[0] == T_ABSTRACT)
//                        || ($token[0] == T_NAMESPACE)
                        || ($token[0] == T_INTERFACE)) {
                        $class_token = true;
                    } else if ($class_token && $token[0] == T_STRING) {
                        echo "Found class: $token[1]\n";
                        $class_token = false;
                    }
                }
            }
        }

        return $view;
    }
}
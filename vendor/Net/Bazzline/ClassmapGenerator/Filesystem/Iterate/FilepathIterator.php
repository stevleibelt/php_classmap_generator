<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Iterate;

use Net\Bazzline\ClassmapGenerator\Factory\DirectoryIteratorFactory;
use Net\Bazzline\ClassmapGenerator\Factory\FileAnalyzerFactory;

/**
 * @author stev leibelt
 * @since 2013-03-01
 */
class FilepathIterator implements IterateInterface
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
    private $blacklistedPaths;

    /**
     * @author stev leibelt
     * @since 2013-03-01
     * @var array
     */
    private $whitelistedPaths;

    /**
     * @author stev leibelt
     * @param string $basepath
     * @since 2013-03-01
     * @throws \InvalidArgumentException
     */
    public function setBasepath($basepath)
    {
        $this->basepath = (string) $basepath;
    }

    /**
     * @author stev leibelt
     * @param string $whitelistedPath
     * @since 2013-03-01
     * @throws \InvalidArgumentException
     */
    public function addWhitelistedPath($whitelistedPath)
    {
        $this->whitelistedPaths[] = (string) $whitelistedPath;
    }

    /**
     * @author stev leibelt
     * @param string $blacklistedPath
     * @since 2013-03-01
     * @throws \InvalidArgumentException
     */
    public function addBlacklistedPath($blacklistedPath)
    {
        $this->blacklistedPaths[] = (string) $blacklistedPath;
    }

    /**
     * @author stev leibelt
     * @return array
     * @since 2013-02-28
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function iterate()
    {
        $classmapFileContent = array();
        if (is_null($this->basepath)) {
            $this->setBasepath('');
        }

        foreach ($this->whitelistedPaths as $path) {
            $classmapFileContent += $this->iteratePath($path, $this->basepath);
        }

        return $classmapFileContent;
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
        $classNameToFilePathEntries = array();

        if (is_dir($path)) {
            $directoryIterator = DirectoryIteratorFactory::create(
                array(
                    DirectoryIteratorFactory::OPTION_PATH => $path,
                    DirectoryIteratorFactory::OPTION_BLACKISTED_DIRECTORIES => $this->blacklistedPaths
                )
            );
            $fileAnalyzer = FileAnalyzerFactory::create(
                array(
                    FileAnalyzerFactory::OPTION_BASEPATH => $basepath
                )
            );

            foreach ($directoryIterator as $entry) {
                $currentPath = $path . DIRECTORY_SEPARATOR . $entry->getFilename();
                if ($entry->isDir()) {
                    $classNameToFilePathEntries = array_merge($classNameToFilePathEntries, $this->iteratePath($currentPath, $basepath));
                } else {
                    $classNameToFilePathEntries = array_merge($classNameToFilePathEntries, $fileAnalyzer->analyze($currentPath));
                }
            }
        }

        return $classNameToFilePathEntries;
    }
}
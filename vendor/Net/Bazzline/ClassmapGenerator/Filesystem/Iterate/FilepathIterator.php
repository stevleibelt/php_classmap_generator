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
            $classmapFileContent += $this->iteratePath(realpath($this->basepath . DIRECTORY_SEPARATOR . $path));
        }

        return $classmapFileContent;
    }

    /**
     * @author stev leibelt
     * @param string $path
     * @return array
     * @since 2013-02-28
     */
    private function iteratePath($realPath)
    {
        $classNameToFilePathEntries = array();

        if (is_string($realPath)) {
            $directoryIterator = DirectoryIteratorFactory::create(
                array(
                    DirectoryIteratorFactory::OPTION_PATH => $realPath,
                    DirectoryIteratorFactory::OPTION_BLACKISTED_DIRECTORIES => $this->blacklistedPaths
                )
            );
            $fileAnalyzer = FileAnalyzerFactory::create(
                array(
                    FileAnalyzerFactory::OPTION_BASEPATH => $this->basepath
                )
            );

            if (is_dir($realPath)) {
                foreach ($directoryIterator as $entry) {
                    $currentPath = $realPath . DIRECTORY_SEPARATOR . $entry->getFilename();

                    if ($entry->isDir()) {
                        $classNameToFilePathEntries += $this->iteratePath($currentPath);
                    } else {
                        $classNameToFilePathEntries += $fileAnalyzer->analyze($currentPath);
                    }
                }
            } else if (is_file($realPath)) {
                if (!in_array($realPath, $classNameToFilePathEntries)) {
                    $classNameToFilePathEntries += $fileAnalyzer->analyze($realPath);
                }
            }
        }

        return $classNameToFilePathEntries;
    }
}
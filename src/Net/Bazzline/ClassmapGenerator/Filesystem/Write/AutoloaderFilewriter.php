<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Write;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class AutoloaderFilewriter extends FilewriterAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-03-05
     * @var string
     */
    private $filePathClassmap;

    /**
     * @author stev leiblet
     * @param string $filePathClassmap
     * @since 2013-03-05
     */
    public function setFilePathClassmap($filePathClassmap)
    {
        $this->filePathClassmap = $filePathClassmap;
    }

    /**
     * @author stev leiblet
     * @return string
     * @since 2013-03-05
     */
    public function getFilePathClassmap()
    {
        return $this->filePathClassmap;
    }

    /**
     * @author stev leibelt
     * @return boolean
     * @since 22013-03-03
     * @throws \RuntimeException
     */
    public function write()
    {
        if ($this->fileExists()) {
            return false;
        }

        $classmapFilename = $this->getConfiguration()->getFilenameClassmap();

        $uniqueIdentifier = sha1(__CLASS__ . microtime());
        $date = date('Y-m-d H:i:s');
        $data = <<<EOC
<?php
/**
 * Created with Net\Bazzline\ClassmapGenerator
 * @author stev leibelt
 * @since $date
 */

/**
 * Global function registering the usage of "$classmapFilename" classmap.
 *
 * @param string \$className
 *
 * @author stev leibelt
 * @since 2013-02-27
 */
function autoloadFromFilesystem_$uniqueIdentifier(\$classname)
{
    \$fileName = str_replace('\\\\', DIRECTORY_SEPARATOR, \$classname) . '.php';
    \$includePaths = array(
        realpath(__DIR__ . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
    );

    foreach (\$includePaths as \$includePath) {
        \$filepath = realpath(\$includePath . DIRECTORY_SEPARATOR . \$filename);

        if (file_exists(\$filepath)) {
            require_once \$filepath;

            break;
        }
    }
}
EOC;

        $relativeAutoloaderFilepath = $this
            ->getFilesystem()
            ->getRelativePathToCurrentWorkingDirectory(
                $this
                    ->getConfiguration()
                    ->getFilepathAutoloader()
            );
        $relativeClassmapFilepath = $this
            ->getFilesystem()
            ->getRelativePathToCurrentWorkingDirectory(
                $this
                    ->getConfiguration()
                    ->getFilepathClassmap()
            );
        $relativeClassmapFilepathToAutoloaderFilepath = $this
            ->getFilesystem()
            ->getRelativePath(
                $relativeAutoloaderFilepath,
                $relativeClassmapFilepath
            ) .
            DIRECTORY_SEPARATOR .
            $this
                ->getConfiguration()
                ->getFilenameClassmap();
         $relativeClassmapFilepathToCurrentWorkingDirectoryPath = $this
            ->getFilesystem()
            ->getRelativePathToCurrentWorkingDirectory(
                $relativeClassmapFilepath
            ) .
            DIRECTORY_SEPARATOR .
            $this
                ->getConfiguration()
                ->getFilenameClassmap();

        if (file_exists($relativeClassmapFilepathToCurrentWorkingDirectoryPath)) {
            $data .= <<<EOC


/**
 * Global function providing a fallback mechanism
 *
 * @param string \$className
 *
 * @author stev leibelt
 * @since 2013-02-28
 */
function autoloadFromFilesystemWithClassmap_$uniqueIdentifier(\$classname)
{
    \$classnameToFilepath = require_once '$relativeClassmapFilepathToAutoloaderFilepath';

    if (isset(\$classnameToFilepath[\$classname])) {
        require_once \$classnameToFilepath[\$classname];
    } else {
        return false;
    }
}

if (file_exists('$relativeClassmapFilepathToAutoloaderFilepath')) {
    spl_autoload_register('autoloadFromFilesystemWithClassmap_$uniqueIdentifier');
}

EOC;
        }
        $data .= <<<EOC

spl_autoload_register('autoloadFromFilesystem_$uniqueIdentifier');
EOC;

        return (file_put_contents($this->getFilepath(), $data) !== false);
    }
}
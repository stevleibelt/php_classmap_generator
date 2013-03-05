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

        $uniqueIdentifier = sha1(__CLASS__ . microtime());
        $date = date('Y-m-d') . ' ' . date('H:i:s');
        $data = <<<EOC
<?php
/**
 * Created with Net\Bazzline\ClassmapGenerator
 * Creationdate $date
 */

/**
 * @author stev leibelt
 * @param string \$className
 * @since 2013-02-27
 */
function autoloadFromFilesystem_$uniqueIdentifier(\$className)
{
    \$fileName = str_replace('\\\\', DIRECTORY_SEPARATOR, \$className) . '.php';
    \$includePaths = array(
        realpath(__DIR__ . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
    );

    foreach (\$includePaths as \$includePath) {
        \$filePath = realpath(\$includePath . DIRECTORY_SEPARATOR . \$fileName);

        if (file_exists(\$filePath)) {
            require \$filePath;

            break;
        }
    }
}
EOC;

        if (file_exists($this->getFilePathClassmap())) {
            $startPositionOfFilename = strrpos($this->getFilePathClassmap(), DIRECTORY_SEPARATOR) + strlen(DIRECTORY_SEPARATOR);
            $fileNameClassmap = substr($this->getFilePathClassmap(), $startPositionOfFilename);
            $data .= <<<EOC


/**
 * @author stev leibelt
 * @param string \$className
 * @since 2013-02-28
 */
function autoloadFromFilesystemWithClassmap_$uniqueIdentifier(\$classname)
{
    \$classnameToFilepath = require '$fileNameClassmap';

    if (isset(\$classnameToFilepath[\$classname])) {
        require \$classnameToFilepath[\$classname];
    } else {
        return false;
    }
}

if (file_exists('$fileNameClassmap')) {
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
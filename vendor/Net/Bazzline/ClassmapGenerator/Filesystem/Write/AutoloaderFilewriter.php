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
     * @since 2013-03-03
     * @var string
     */
    private $relativePathToProjectRoot;

    /**
     * @author stev leibelt
     * @param string $relativePathToProjectRoot
     * @since 2013-03-03
     */
    public function setRelativePathToProjectRoot($relativePathToProjectRoot)
    {
        $this->relativePathToProjectRoot = $relativePathToProjectRoot;
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
        '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'
    );

    foreach (\$includePaths as \$includePath) {
        \$filePath = realpath(\$includePath . DIRECTORY_SEPARATOR . \$fileName);

        if (file_exists(\$filePath)) {
            require \$filePath;

            break;
        }
    }
}

/**
 * @author stev leibelt
 * @param string \$className
 * @since 2013-02-28
 */
function autoloadFromFilesystemWithClassmap_$uniqueIdentifier(\$classname)
{
    \$classnameToFilepath = require 'autoloader_classmap.php';
    \$pathToProjectRoot = $this->relativePathToProjectRoot;

    if (isset(\$classnameToFilepath[\$classname])) {
        require \$pathToProjectRoot . DIRECTORY_SEPARATOR . \$classnameToFilepath[\$classname];
    } else {
        return false;
    }
}

if (file_exists('autoloader_classmap.php')) {
    spl_autoload_register('autoloadFromFilesystemWithClassmap_$uniqueIdentifier');
}
spl_autoload_register('autoloadFromFilesystem_$uniqueIdentifier');'
EOC;

exit($this->getFilepath());
        return (file_put_contents($this->getFilepath(), $data) !== false);
    }
}
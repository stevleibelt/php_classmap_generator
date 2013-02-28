<?php
/**
 * @author stev leibelt
 * @param string $className
 * @since 2013-02-27
 */
function autoloadFromFilesystem($className)
{
    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    $includePaths = array(
        '../..'
    );

    foreach ($includePaths as $includePath) {
        $filePath = $includePath . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            require $filePath;

            break;
        }
    }
}

/**
 * @author stev leibelt
 * @param string $className
 * @since 2013-02-28
 */
function autoloadFromFilesystemWithClassmap($classname)
{
    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
    $classnameToFilepath = require 'autoloader_classmap.php';

    if (isset($classnameToFilepath[$classname])) {
        require $classnameToFilepath[$classname];
    } else {
        return false;
    }
}

spl_autoload_register('autoloadFromFilesystemWithClassmap');
spl_autoload_register('autoloadFromFilesystem');

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
        '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'
    );

    foreach ($includePaths as $includePath) {
        $filePath = realpath($includePath . DIRECTORY_SEPARATOR . $fileName);

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
    $classnameToFilepath = require 'autoloader_classmap.php';
    $pathToProjectRoot = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

    if (isset($classnameToFilepath[$classname])) {
        require $pathToProjectRoot . $classnameToFilepath[$classname];
    } else {
        return false;
    }
}

if (file_exists('autoloader_classmap.php')) {
    spl_autoload_register('autoloadFromFilesystemWithClassmap');
}
spl_autoload_register('autoloadFromFilesystem');

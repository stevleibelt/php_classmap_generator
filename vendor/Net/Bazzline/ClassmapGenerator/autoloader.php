<?php
/**
 * Created with Net\Bazzline\ClassmapGenerator
 * Creationdate 2013-03-03 22:15:48
 */

/**
 * @author stev leibelt
 * @param string $className
 * @since 2013-02-27
 */
function autoloadFromFilesystem_66b5698bbef0fe578bde446269223ed37b838ca3($className)
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
function autoloadFromFilesystemWithClassmap_66b5698bbef0fe578bde446269223ed37b838ca3($classname)
{
    $classnameToFilepath = require 'autoloader_classmap.php';
    $pathToProjectRoot = '../../../..';

    if (isset($classnameToFilepath[$classname])) {
        require $pathToProjectRoot . DIRECTORY_SEPARATOR . $classnameToFilepath[$classname];
    } else {
        return false;
    }
}

if (file_exists('autoloader_classmap.php')) {
    spl_autoload_register('autoloadFromFilesystemWithClassmap_66b5698bbef0fe578bde446269223ed37b838ca3');
}
spl_autoload_register('autoloadFromFilesystem_66b5698bbef0fe578bde446269223ed37b838ca3');
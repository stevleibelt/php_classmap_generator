<?php
/**
 * Created with Net\Bazzline\ClassmapGenerator
 * Creationdate 2013-03-05 00:42:17
 */

/**
 * @author stev leibelt
 * @param string $className
 * @since 2013-02-27
 */
function autoloadFromFilesystem_41a6ccee4d5d979701192268897425732dfb18e8($className)
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
function autoloadFromFilesystemWithClassmap_41a6ccee4d5d979701192268897425732dfb18e8($classname)
{
    $classnameToFilepath = require 'autoloader_classmap.php';

    if (isset($classnameToFilepath[$classname])) {
        require $classnameToFilepath[$classname];
    } else {
        return false;
    }
}

if (file_exists('autoloader_classmap.php')) {
    spl_autoload_register('autoloadFromFilesystemWithClassmap_41a6ccee4d5d979701192268897425732dfb18e8');
}
spl_autoload_register('autoloadFromFilesystem_41a6ccee4d5d979701192268897425732dfb18e8');

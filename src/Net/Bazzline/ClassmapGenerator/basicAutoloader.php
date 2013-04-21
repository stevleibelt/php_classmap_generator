<?php

function netBazzlineClassmapGeneratorBasicAutoloader($className)
{
    $classNameWithRemovedNamespace = str_replace('Net\\Bazzline\\ClassmapGenerator\\', '', $className);
    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithRemovedNamespace) . '.php';
    $includePaths = array(
        realpath(__DIR__ . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
    );

    foreach ($includePaths as $includePath) {
        $filePath = realpath($includePath . DIRECTORY_SEPARATOR . $fileName);

        if (file_exists($filePath)) {
            require $filePath;

            break;
        }
    }
}

spl_autoload_register('netBazzlineClassmapGeneratorBasicAutoloader');

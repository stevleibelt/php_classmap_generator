<?php

function netBazzlineClassmapGeneratorBasicAutoloader($classname)
{
    $classNameWithRemovedNamespace = str_replace('Net\\Bazzline\\ClassmapGenerator\\', '', $classname);
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithRemovedNamespace) . '.php';
    $includePaths = array(
        realpath(__DIR__ . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
    );

    foreach ($includePaths as $includePath) {
        $filepath = realpath($includePath . DIRECTORY_SEPARATOR . $filename);

        if (file_exists($filepath)) {
            require_once $filepath;

            break;
        } else {
            echo var_export(
                array(
                    'classname' => $classname,
                    'filename' => $filename,
                    'filepath' => $filepath,
                    'includedPath' => $includePath
                ),
                true
            ) . PHP_EOL;
        }
    }
}

spl_autoload_register('netBazzlineClassmapGeneratorBasicAutoloader');

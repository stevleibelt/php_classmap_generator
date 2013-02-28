<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem;

use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class FileAnalyzer
{
    /**
     * @author stev leibelt
     * @param string $filepath
     * @return string
     * @since 2013-02-27
     * @throws InvalidArgumentException
     */
    public function getClassname($filepath)
    {
        if (!file_exists($filename)) {
            $message = 'Given filename "' . $filename . '" doesn\'t exist';

            throw new InvalidArgumentException($message);
        }
    }
}
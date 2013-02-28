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
        if (!file_exists($filepath)) {
            $message = 'Given filename "' . $filepath . '" doesn\'t exist';

            throw new InvalidArgumentException($message);
        }

        $classNames = array();
        //http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
        //http://wiki.birth-online.de/snippets/php/get-classes-in-file
        $fileContent = file_get_contents($filepath);
        $tokens = token_get_all($fileContent);
        $class_token = false;
        foreach ($tokens as $token) {
            if ( !is_array($token) ) continue;
            if (($token[0] == T_CLASS)
                || ($token[0] == T_ABSTRACT)
//                        || ($token[0] == T_NAMESPACE)
                || ($token[0] == T_INTERFACE)) {
                $class_token = true;
            } else if ($class_token && $token[1] == T_STRING) {
                $classNames[] = $token[1];
                $class_token = false;
            }
        }

        return $classNames;
    }
}
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
     * 
     * based on: http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
     */
    function getClassname($filepath)
    {
        if (!file_exists($filepath)) {
            $message = 'Given filename "' . $filepath . '" doesn\'t exist';

            throw new InvalidArgumentException($message);
        }

        $classNames = array();
        $namespacePositions = array();
        $final = array();
        $wasNamespaceFound = false;
        $namespaceIterator = 0;

        $errorReporting = error_reporting();
        error_reporting(E_ALL ^ E_NOTICE);

        $fileContent = file_get_contents($filepath);
        $tokens = token_get_all($fileContent);
        $numberOfTokens = count($tokens);

        for ($tokenIterator = 0; $tokenIterator < $numberOfTokens; $tokenIterator++) 
        {
            if(!$wasNamespaceFound && $tokens[$tokenIterator][0] == T_NAMESPACE)
            {
                $namespacePositions[$namespaceIterator]['start'] = $tokenIterator;
                $wasNamespaceFound = true;
            }
            elseif( $wasNamespaceFound && ($tokens[$tokenIterator] == ';' || $tokens[$tokenIterator] == '{') )
            {
                $namespacePositions[$namespaceIterator]['end']= $tokenIterator;
                $namespaceIterator++;
                $wasNamespaceFound = false;
            }
            elseif ($tokenIterator-2 >= 0 && $tokens[$tokenIterator - 2][0] == T_CLASS && $tokens[$tokenIterator - 1][0] == T_WHITESPACE && $tokens[$tokenIterator][0] == T_STRING) 
            {
                if($tokenIterator-4 >=0 && $tokens[$tokenIterator - 4][0] == T_ABSTRACT)
                {
                    $classNames[$namespaceIterator][] = array('name' => $tokens[$tokenIterator][1], 'type' => 'ABSTRACT CLASS');
                }
                else
                {
                    $classNames[$namespaceIterator][] = array('name' => $tokens[$tokenIterator][1], 'type' => 'CLASS');
                }
            }
            elseif ($tokenIterator-2 >= 0 && $tokens[$tokenIterator - 2][0] == T_INTERFACE && $tokens[$tokenIterator - 1][0] == T_WHITESPACE && $tokens[$tokenIterator][0] == T_STRING)
            {
                $classNames[$namespaceIterator][] = array('name' => $tokens[$tokenIterator][1], 'type' => 'INTERFACE');
            }
        }
        error_reporting($errorReporting);
        if (empty($classNames)) return array();

        if(!empty($namespacePositions))
        {
            foreach($namespacePositions as $namespacePositionIterator => $namespacePosition)
            {
                $namespace = '';
                for($tokenIterator = $namespacePosition['start'] + 1; $tokenIterator < $namespacePosition['end']; $tokenIterator++)
                    $namespace .= $tokens[$tokenIterator][1];

                $namespace = trim($namespace);
                $final[$namespacePositionIterator] = array('namespace' => $namespace, 'classes' => $classNames[$namespacePositionIterator+1]);
            }
            $classNames = $final;
        }

        $classnameToFilepath = array();
        foreach ($classNames as $fileContent) {
            $namespace = $fileContent['namespace'];
            foreach ($fileContent['classes'] as $class) {
                $classnameToFilepath[$namespace . '\\' . $class['name']] = $filepath;
            }
        }

        return $classnameToFilepath;
    }
}
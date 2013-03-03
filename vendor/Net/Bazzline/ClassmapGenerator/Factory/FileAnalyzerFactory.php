<?php

namespace Net\Bazzline\ClassmapGenerator\Factory;

use Net\Bazzline\ClassmapGenerator\Filesystem\FileAnalyzer;
use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class FileAnalyzerFactory implements FactoryInterface
{
    const OPTION_BASEPATH = 'basepath';

    /**
     * @author stev leibelt
     * @param array $options
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\FileAnalyzer
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public static function create(array $options) 
    {
        self::validateOptions($options);

        $fileAnalyzer = new FileAnalyzer();
        $fileAnalyzer->setBasepath($options[self::OPTION_BASEPATH]);

        return $fileAnalyzer;
    }

    /**
     * @author stev leibelt
     * @param array $options
     * @since 2013-03-03
     * @throws InvalidArgumentException
     */
    private static function validateOptions(array $options)
    {
        $mandatoryOptions = array(
            self::OPTION_BASEPATH
        );

        foreach ($mandatoryOptions as $mandatoryOption) {
            if (!isset($options[$mandatoryOption])) {
                $message = 'Option "' . $mandatoryOption . '" is mandatory.';

                throw new InvalidArgumentException($message);
            }
        }
    }
}
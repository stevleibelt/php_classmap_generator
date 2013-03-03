<?php

namespace Net\Bazzline\ClassmapGenerator\Factory;

use Net\Bazzline\ClassmapGenerator\Filesystem\Write\AutoloaderFilewriter;
use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class AutoloaderFilewriterFactory implements FactoryInterface
{
    const OPTION_FILE_PATH = 'filePath';
    const OPTION_RELATIVE_PATH_TO_PROJECT_ROOT = 'relativePathToProjectRoot';

    /**
     * @author stev leibelt
     * @param array $options
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public static function create(array $options) 
    {
        self::validateOptions($options);

        $autoloaderFileWriter = new AutoloaderFilewriter();
        $autoloaderFileWriter->setFilePath($options[self::OPTION_FILE_PATH]);
        $autoloaderFileWriter->setRelativePathToProjectRoot($options[self::OPTION_RELATIVE_PATH_TO_PROJECT_ROOT]);

        return $autoloaderFileWriter;
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
            self::OPTION_FILE_PATH,
            self::OPTION_RELATIVE_PATH_TO_PROJECT_ROOT
        );

        foreach ($mandatoryOptions as $mandatoryOption) {
            if (!isset($options[$mandatoryOption])) {
                $message = 'Option "' . $mandatoryOption . '" is mandatory.';

                throw new InvalidArgumentException($message);
            }
        }
    }
}
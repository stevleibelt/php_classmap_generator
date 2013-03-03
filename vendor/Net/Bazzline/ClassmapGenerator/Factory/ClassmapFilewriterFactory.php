<?php

namespace Net\Bazzline\ClassmapGenerator\Factory;

use Net\Bazzline\ClassmapGenerator\Filesystem\Write\ClassmapFilewriter;
use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class ClassmapFilewriterFactory implements FactoryInterface
{
    const OPTION_FILE_PATH = 'filePath';
    const OPTION_FILE_DATA = 'fileData';

    /**
     * @author stev leibelt
     * @param array $options
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public static function create(array $options) 
    {
        self::validateOptions($options);

        $classmapFileWriter = new ClassmapFilewriter();
        $classmapFileWriter->setFilePath($options[self::OPTION_FILE_PATH]);
        $classmapFileWriter->setFiledata($options[self::OPTION_FILE_DATA]);

        return $classmapFileWriter;
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
            self::OPTION_FILE_DATA
        );

        foreach ($mandatoryOptions as $mandatoryOption) {
            if (!isset($options[$mandatoryOption])) {
                $message = 'Option "' . $mandatoryOption . '" is mandatory.';

                throw new InvalidArgumentException($message);
            }
        }
    }
}
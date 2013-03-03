<?php

namespace Net\Bazzline\ClassmapGenerator\Factory;

use DirectoryIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\Filter\PhpFileOrDirectoryFilterIterator;
use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class DirectoryIteratorFactory implements FactoryInterface
{
    const OPTION_PATH = 'path';
    const OPTION_BLACKISTED_DIRECTORIES = 'blacklistedDirectories';

    /**
     * @author stev leibelt
     * @param array $options
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Filter\PhpFileOrDirectoryFilterIterator
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public static function create(array $options) 
    {
        self::validateOptions($options);

        $directoryIterator = new PhpFileOrDirectoryFilterIterator(
            new DirectoryIterator($options[self::OPTION_PATH])
        );
        $directoryIterator->setDirectoryNamesToFilterOut($options[self::OPTION_BLACKISTED_DIRECTORIES]);

        return $directoryIterator;
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
            self::OPTION_PATH,
            self::OPTION_BLACKISTED_DIRECTORIES
        );

        foreach ($mandatoryOptions as $mandatoryOption) {
            if (!isset($options[$mandatoryOption])) {
                $message = 'Option "' . $mandatoryOption . '" is mandatory.';

                throw new InvalidArgumentException($message);
            }
        }
    }
}
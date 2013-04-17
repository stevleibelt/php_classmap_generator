<?php

namespace Net\Bazzline\ClassmapGenerator\Factory;

use Net\Bazzline\ClassmapGenerator\Filesystem\Iterate\FilepathIterator;
use InvalidArgumentException;

/**
 * @author stev leibelt
 * @since 2013-03-03
 */
class FilepathIteratorFactory implements FactoryInterface
{
    const OPTION_BASE_PATH = 'basePath';
    const OPTION_WHITELISTED_DIRECTORIES = 'whitelistedDirectories';
    const OPTION_BLACKISTED_DIRECTORIES = 'blacklistedDirectories';

    /**
     * @author stev leibelt
     * @param array $options
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Iterate\FilepathIterator
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public static function create(array $options) 
    {
        self::validateOptions($options);

        $filepathIterator = new FilepathIterator();
        $filepathIterator->setBasepath($options[self::OPTION_BASE_PATH]);

        foreach ($options[self::OPTION_WHITELISTED_DIRECTORIES] as $whitelistedPath) {
            $filepathIterator->addWhitelistedPath($whitelistedPath);
        }

        foreach ($options[self::OPTION_BLACKISTED_DIRECTORIES] as $blacklistedPath) {
            $filepathIterator->addBlacklistedPath($blacklistedPath);
        }

        return $filepathIterator;
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
            self::OPTION_BASE_PATH,
            self::OPTION_BLACKISTED_DIRECTORIES,
            self::OPTION_WHITELISTED_DIRECTORIES
        );

        foreach ($mandatoryOptions as $mandatoryOption) {
            if (!isset($options[$mandatoryOption])) {
                $message = 'Option "' . $mandatoryOption . '" is mandatory.';

                throw new InvalidArgumentException($message);
            }
        }
    }
}
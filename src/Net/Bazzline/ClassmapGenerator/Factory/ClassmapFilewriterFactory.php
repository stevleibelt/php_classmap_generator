<?php
/**
 * @author stev leibelt
 * @since 2013-03-03
 */

namespace Net\Bazzline\ClassmapGenerator\Factory;

use Net\Bazzline\ClassmapGenerator\Filesystem\Write\ClassmapFilewriter;
use InvalidArgumentException;

class ClassmapFilewriterFactory implements FactoryInterface
{
    /**
     * @var string
     * @author stev leibelt
     * @since 2013-04-25
     */
    const ARGUMENT_FILE_PATH = 'filePath';

    /**
     * @var string
     * @author stev leibelt
     * @since 2013-04-25
     */
    const ARGUMENT_FILE_DATA = 'fileData';

    /**
     * @param array $arguments - collection of arguments
     *
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Write\ClassmapFilewriter
     * @author stev leibelt
     * @since 2013-03-03
     * @throws \InvalidArgumentException
     */
    public static function create(array $arguments)
    {
        self::validateArguments($arguments);

        $classmapFileWriter = new ClassmapFilewriter();
        $classmapFileWriter->setFilePath($arguments[self::ARGUMENT_FILE_PATH]);
        $classmapFileWriter->setFiledata($arguments[self::ARGUMENT_FILE_DATA]);

        return $classmapFileWriter;
    }

    /**
     * Validates the arguments
     *
     * @param array $arguments - collection of given arguments
     *
     * @author stev leibelt
     * @since 2013-03-03
     * @throws InvalidArgumentException
     */
    private static function validateArguments(array $arguments)
    {
        $mandatoryArguments = array(
            self::ARGUMENT_FILE_PATH,
            self::ARGUMENT_FILE_DATA
        );

        foreach ($mandatoryArguments as $mandatoryArgument) {
            if (!isset($arguments[$mandatoryArgument])) {
                $message = 'Option "' . $mandatoryArgument . '" is mandatory.';

                throw new InvalidArgumentException($message);
            }
        }
    }
}
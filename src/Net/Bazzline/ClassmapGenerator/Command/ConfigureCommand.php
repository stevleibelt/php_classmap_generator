<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Net\Bazzline\ClassmapGenerator\Filesystem\Write\ConfigurationFilewriter;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class ConfigureCommand extends CommandAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-04-21
     */
    protected  function configure()
    {
        $this
            ->setName('configure')
            ->setDescription('Configures classmap generator')
        ;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = array();

        $configuration['filename'] = $this->askForFilenames($input, $output);
        $configuration['filepath'] = $this->askForFilepaths($input, $output);
        $configuration['whitelist'] = $this->askForWhitelistPaths($input, $output);
        $configuration['blacklist'] = $this->askForBlacklistPaths($input, $output);
        $configuration['createAutoloaderFile'] = $this->askCreationOfAutoloaderFile($input, $output);
        $configuration['defaultTimezone'] = $this->askForDefaultTimezone($input, $output);
        $overwrite = $this->askForOverwriteIfFileExists($input, $output);

        $fileName = 'classmap_generator_configuration.php';
        $filePath = getcwd();

        $output->writeln('<info>Writing configuration to "' . $filePath .
            DIRECTORY_SEPARATOR . $fileName . '</info>');
        if ($this->writeConfiguration($configuration, $fileName, $filePath, $overwrite)) {
            $output->writeln('<info>Configuration was written.</info>');
        } else {
            $output->writeln('<error>Configuration was not written.</error>');
        }
    }

    /**
     * @author stev leibelt
     * @param array $data
     * @param string $fileName
     * @param string $filePath
     * @param boolean $overwrite
     * @return bool
     * @since 2013-04-21
     */
    private function writeConfiguration(array $data, $fileName, $filePath, $overwrite = false)
    {
        $writer = new ConfigurationFilewriter();
        $writer->setFilePath($filePath . DIRECTORY_SEPARATOR . $fileName);
        $writer->setFiledata($data);

        if ($overwrite) {
            return $writer->overwrite();
        } else {
            return $writer->write();
        }
    }

    /**
     * @author stev leibelt
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     * @since 2013-04-22
     */
    private function askForOverwriteIfFileExists(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $default = 'n';

        $overwriteIfFileExists = ($dialog->askAndValidate(
            $output,
            '<question>Should i overwrite the configuration file if it exists (y/n - default is "y")?</question>' . PHP_EOL,
            function ($answer) {
                if (($answer != '')
                    && (!in_array($answer, array('y', 'n')))) {
                    throw new RuntimeException(
                        'y or n, please choose.'
                    );
                }

                return $answer;
            },
            true,
            $default
        ) == 'y') ? true : false;

        return $overwriteIfFileExists;
    }

    /**
     * @author stev leibelt
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     * @since 2013-04-21
     */
    private function askCreationOfAutoloaderFile(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $default = 'y';

        $createAutoloaderFile = ($dialog->askAndValidate(
            $output,
            '<question>Should i create a autoloader file (y/n - default is "y")?</question>' . PHP_EOL,
            function ($answer) {
                if (($answer != '')
                    && (!in_array($answer, array('y', 'n')))) {
                    throw new RuntimeException(
                        'y or n, please choose.'
                    );
                }

                return $answer;
            },
            true,
            $default
        ) == 'y') ? true : false;

        return $createAutoloaderFile;
    }

    /**
     * @author stev leibelt
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     * @since 2013-04-21
     */
    private function askForFilenames(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $defaultClassmapFilename = 'autoloader_classmap';
        $defaultAutoloaderFilename = 'generated_autoloader';

        $classmapFilename = $dialog->askAndValidate(
            $output,
            '<question>Please enter a name for the classmap file (default is "' . $defaultClassmapFilename . '".' . PHP_EOL .
                'You don\'t have to suffix the file with ".php".</question>' . PHP_EOL,
            function ($answer) {
                if (strlen($answer) < 4) {
                    throw new RunTimeException(
                        'The filename should be at least four characters long.'
                    );
                }

                return $answer;
            },
            false,
            $defaultClassmapFilename
        );

        $autoloaderFilename = $dialog->askAndValidate(
            $output,
            '<question>Please enter a name for the autoloader file (default is "' . $defaultAutoloaderFilename . '".' . PHP_EOL .
                'You don\'t have to suffix the file with ".php".</question>' . PHP_EOL,
            function ($answer) {
                if (strlen($answer) < 4) {
                    throw new RunTimeException(
                        'The filename should be at least four characters long.'
                    );
                }

                return $answer;
            },
            false,
            $defaultAutoloaderFilename
        );

        return array(
            'classmap' => $classmapFilename . '.php',
            'autoloader' => $autoloaderFilename . '.php'
        );
    }

    /**
     * @author stev leibelt
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     * @since 2013-04-21
     */
    private function askForFilepaths(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $defaultPath = getcwd();

        $classmapFilepath = $dialog->askAndValidate(
            $output,
            '<question>Please enter the path where you want to store the classmap file (default is "' . $defaultPath . '").</question>' . PHP_EOL,
            function ($answere) {
                if ((!is_dir(realpath($answere)))
                    || (!is_writeable(realpath($answere)))) {
                    throw new RuntimeException(
                        'The path must exists and has to be writeable.'
                    );

                    return $answere;
                }
            },
            false,
            $defaultPath
        );

        $autoloaderFilepath = $dialog->askAndValidate(
            $output,
            '<question>Please enter the path where you want to store the autoloader file (default is "' . $defaultPath . '").</question>' . PHP_EOL,
            function ($answere) {
                if ((!is_dir(realpath($answere)))
                    || (!is_writeable(realpath($answere)))) {
                    throw new RuntimeException(
                        'The path must exists and has to be writeable.'
                    );

                    return $answere;
                }
            },
            false,
            $defaultPath
        );

        $configurationFilepath = $dialog->askAndValidate(
            $output,
            '<question>Please enter the path where you want to store the configuration file (default is "' . $defaultPath . '").</question>' . PHP_EOL,
            function ($answere) {
                if ((!is_dir(realpath($answere)))
                    || (!is_writeable(realpath($answere)))) {
                    throw new RuntimeException(
                        'The path must exists and has to be writeable.'
                    );

                    return $answere;
                }
            },
            false,
            $defaultPath
        );

        return array(
            'classmap' => $classmapFilepath,
            'autloader' => $autoloaderFilepath,
            'configuration' => $configurationFilepath
        );
    }

    /**
     * @author stev leibelt
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     * @since 2013-04-21
     */
    private function askForWhitelistPaths(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $whitelist = array();

        $output->writeln('<info>Add paths (directory or file) you want to whitelist (parsed for sure). Enter empty string when finished.</info>');
        while (true) {
            $path = $dialog->askAndValidate(
                $output,
                '<question>Enter path you want to whitelist.</question>' . PHP_EOL,
                function ($answer) {
                    if (($answer != '')
                        && (!is_readable(realpath(($answer))))) {
                        throw new RuntimeException(
                            'Provided path is not readable'
                        );
                    }

                    return $answer;
                },
                false,
                ''
            );

            if ($path == '') {
                break;
            }
            $whitelist[] = $path;
        }

        return $whitelist;
    }

    /**
     * @author stev leibelt
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     * @since 2013-04-21
     */
    private function askForBlacklistPaths(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $blacklist = array();

        $output->writeln('<info>Add paths (directory or file) you want to blacklist (will never be parsed). Enter empty string when finished.</info>');
        while (true) {
            $path = $dialog->askAndValidate(
                $output,
                '<question>Enter path you want to blacklist.</question>' . PHP_EOL,
                function ($answer) {
                    if (($answer != '')
                        && (!is_readable(realpath(($answer))))) {
                        throw new RuntimeException(
                            'Provided path is not readable'
                        );
                    }

                    return $answer;
                },
                false,
                ''
            );

            if ($path == '') {
                break;
            }
            $blacklist[] = $path;
        }

        return $blacklist;
    }

    /**
     * @author stev leibelt
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     * @since 2013-04-21
     */
    private function askForDefaultTimezone(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $default = 'Europe/Berlin';

        $defaultTimezone = $dialog->askAndValidate(
            $output,
            '<question>Please enter your default timezone (default is "' . $default . '").</question>' . PHP_EOL,
            function ($answer) {
                if (($answer != '')
                    && (strpos($answer, '/') === false)) {
                    throw new RunTimeException(
                        'The timezone needs at least one "/" in it.'
                    );
                }

                return $answer;
            },
            true,
            $default
        );

        return (string) $defaultTimezone;
    }
}
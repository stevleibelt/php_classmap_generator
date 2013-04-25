<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationInterface;
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
     * @var \Net\Bazzline\ClassmapGenerator\Configuration\ConfigurationInterface
     *
     * @author stev leibelt
     * @since 2013-04-25
     */
    private $configuration;

    /**
     * @author stev leibelt
     * @since 2013-04-21
     */
    protected  function configure()
    {
        $this
            ->setName('configure')
            ->setDefinition(
                array(
                    new InputOption('--detail', '-d', InputOption::VALUE_NONE, 'Do a full configuration with all available options.')
                )
            )
            ->setDescription('Configures classmap generator')
            ->setHelp(
                'The <info>%command.name%</info> command guides you through the ' . PHP_EOL .
                    'creation of a configuration file for this console application ' . PHP_EOL .
                    'by asking several questions.' . PHP_EOL .
                    PHP_EOL .
                    'Each question has a default value. That means, you only have ' . PHP_EOL .
                    'to hit enter multiple times to generate a configuration file.'
            )
        ;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configuration = $this->getApplication()->getConfiguration();

        $doDetailedConfiguration = $input->getOption('detail');
        $overwrite = false;

        if (file_exists('classmap_generator_configuration.php')) {
            $overwrite = $this->askForOverwriteIfFileExists($input, $output);
        }
        $this->askCreationOfAutoloaderFile($input, $output);
        $this->askForFilenames($input, $output);
        $this->askForWhitelistPaths($input, $output);

        if ($doDetailedConfiguration) {
            $this->askForBlacklistPaths($input, $output);
            $this->askForFilepaths($input, $output);
            $this->askForDefaultTimezone($input, $output);
        }

        $fileName = 'classmap_generator_configuration.php';
        $filePath = getcwd();

        $output->writeln('<info>Writing configuration to "' . $filePath .
            DIRECTORY_SEPARATOR . $fileName . '</info>');
        if ($this->writeConfiguration($fileName, $filePath, $overwrite)) {
            $output->writeln('<info>Configuration was written.</info>');
        } else {
            $output->writeln('<error>Configuration was not written.</error>');
        }
    }

    /**
     * @param string $fileName
     * @param string $filePath
     * @param boolean $overwrite
     *
     * @return bool
     * @author stev leibelt
     * @since 2013-04-21
     */
    private function writeConfiguration($fileName, $filePath, $overwrite = false)
    {
        $writer = new ConfigurationFilewriter();
        $writer->setFilePath($filePath . DIRECTORY_SEPARATOR . $fileName);
        $writer->setFiledata($this->configuration->toSource());

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
        $default = 'n';
        $question = '<question>Should i overwrite the configuration file if it exists (y/n - default is "y")?</question>';
        $validator = function ($answer) {
                if (($answer != '')
                    && (!in_array($answer, array('y', 'n')))) {
                    throw new RuntimeException(
                        'y or n, please choose.'
                    );
                }

                return $answer;
            };

        $overwriteIfFileExists = ($this->askAndValidate($output, $question, $validator, false, $default) == 'y') ? true : false;

        return $overwriteIfFileExists;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @author stev leibelt
     * @since 2013-04-21
     */
    private function askCreationOfAutoloaderFile(InputInterface $input, OutputInterface $output)
    {
        $default = 'y';
        $question = '<question>Should i create a autoloader file (y/n - default is "y")?</question>';
        $validator = function ($answer) {
                if (($answer != '')
                    && (!in_array($answer, array('y', 'n')))) {
                    throw new RuntimeException(
                        'y or n, please choose.'
                    );
                }

                return $answer;
            };
        $this
            ->configuration
            ->setCreateAutoloaderFile(
                ($this->askAndValidate($output, $question, $validator, false, $default) == 'y') ? true : false
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @author stev leibelt
     * @since 2013-04-21
     */
    private function askForFilenames(InputInterface $input, OutputInterface $output)
    {
        $defaultAutoloaderFilename = $this->configuration->getFilenameAutoloader();
        $defaultClassmapFilename = $this->configuration->getFilenameClassmap();
        $questionClassmapFilename = '<question>Please enter a name for the classmap file (default is "' . $defaultClassmapFilename . '".</question>' . PHP_EOL .
                '<comment>You don\'t have to suffix the file with ".php".</comment>';
        $questionAutoloaderFilename = '<question>Please enter a name for the autoloader file (default is "' . $defaultAutoloaderFilename . '"</question>.' . PHP_EOL .
                '<comment>You don\'t have to suffix the file with ".php".</comment>';
        $validator = function ($answer) {
                if (strlen($answer) < 4) {
                    throw new RunTimeException(
                        'The filename should be at least four characters long.'
                    );
                }

                return $answer;
            };

        if ($this->configuration->createAutoloaderFile()) {
            $this
                ->configuration
                ->setFilenameAutoloader(
                    $this->askAndValidate($output, $questionClassmapFilename, $validator, false, $defaultAutoloaderFilename
                    )
                );
        }
        $this
            ->configuration
            ->setFilenameClassmap(
                $this->askAndValidate($output, $questionAutoloaderFilename, $validator, false, $defaultClassmapFilename)
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @author stev leibelt
     * @since 2013-04-21
     */
    private function askForFilepaths(InputInterface $input, OutputInterface $output)
    {
        $defaultPath = $this->configuration->getFilepathClassmap();
        $questionClassmapFilepath = '<question>Please enter the path where you want to store the classmap file (default is "' . $defaultPath . '").</question>';
        $questionAutoloaderFilepath = '<question>Please enter the path where you want to store the autoloader file (default is "' . $defaultPath . '").</question>';
        $validator = function ($answer) {
                if ((!is_dir(realpath($answer)))
                    || (!is_writeable(realpath($answer)))) {
                    throw new RuntimeException(
                        'The path must exists and has to be writeable.'
                    );
                }

                return $answer;
            };

        $this
            ->configuration
            ->setFilepathAutoloader(
                $this->askAndValidate($output, $questionAutoloaderFilepath, $validator, false, $defaultPath)
            );
        $this
            ->configuration
            ->setFilepathClassmap(
                $this->askAndValidate($output, $questionClassmapFilepath, $validator, false, $defaultPath)
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @author stev leibelt
     * @since 2013-04-21
     */
    private function askForWhitelistPaths(InputInterface $input, OutputInterface $output)
    {
        $default = '';
        $question = '<question>Enter path you want to whitelist.</question>';
        $validator = function ($answer) {
                    if (($answer != '')
                        && (!is_readable(realpath(($answer))))) {
                        throw new RuntimeException(
                            'Provided path is not readable'
                        );
                    }

                    return $answer;
                };
        $whitelist = array();

        $output->writeln('<info>Add paths (directory or file) you want to whitelist (parsed for sure). Enter empty string when finished.</info>');
        while (true) {
            $path = $this->askAndValidate($output, $question, $validator, false, $default);

            if ($path == '') {
                break;
            }
            $whitelist[] = $path;
        }

        $this
            ->configuration
            ->setWhitelist($whitelist);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @author stev leibelt
     * @since 2013-04-21
     */
    private function askForBlacklistPaths(InputInterface $input, OutputInterface $output)
    {
        $default = '';
        $question = '<question>Enter path you want to blacklist.</question>';
        $blacklist = $this->configuration->getBlacklist();

        $validator = function ($answer) {
            if (($answer != '')
                && (!is_readable(realpath(($answer))))) {
                throw new RuntimeException(
                    'Provided path is not readable'
                );
            }

            return $answer;
        };

        $output->writeln('<info>Add paths (directory or file) you want to blacklist (will never be parsed). Enter empty string when finished.</info>');
        while (true) {
            $path = $this->askAndValidate($output, $question, $validator, false, $default);

            if ($path == '') {
                break;
            }
            $blacklist[] = $path;
        }

        $this
            ->configuration
            ->setBlacklist($blacklist);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @author stev leibelt
     * @since 2013-04-21
     */
    private function askForDefaultTimezone(InputInterface $input, OutputInterface $output)
    {
        $question = '<question>Please enter your default timezone (default is "' . $this->configuration->getDefaultTimezone() . '").</question>';
        $validator = function ($answer) {
                if (($answer != '')
                    && (strpos($answer, '/') === false)) {
                    throw new RunTimeException(
                        'The timezone needs at least one "/" in it.'
                    );
                }

                return $answer;
            };

        $this
            ->configuration
            ->setDefaultTimezone(
                $this->askAndValidate($output, $question, $validator, false, $this->configuration->getDefaultTimezone())
            );
    }
}
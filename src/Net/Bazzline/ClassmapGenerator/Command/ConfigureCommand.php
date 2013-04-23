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
     * @since 2013-04-23
     * @var array
     */
    private $defaultBlacklist;

    /**
     * @author stev leibelt
     * @since 2013-04-23
     * @var
     */
    private $defaultPath;

    /**
     * @author stev leibelt
     * @since 2013-04-23
     * @var string
     */
    private $defaultTimezone;

    /**
     * @author stev leibelt
     * @param null $name
     * @since 2013-04-23
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->defaultBlacklist = array(
            '.',
            '..'
        );
        $this->defaultPath = '';
        $this->defaultTimezone = 'Europe/Berlin';
    }

    /**
     * @author stev leibelt
     * @since 2013-04-21
     */
    protected  function configure()
    {
        $this
            ->setName('configure')
            ->setDescription('Configures classmap generator')
            ->addOption('full', null, InputOption::VALUE_NONE, 'Full configuration.')
        ;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = array();
        $doFullConfiguration = $input->getOption('full');

        if (file_exists('classmap_generator_configuration.php')) {
            $overwrite = $this->askForOverwriteIfFileExists($input, $output);
        }
        $configuration['createAutoloaderFile'] = $this->askCreationOfAutoloaderFile($input, $output);
        $configuration['filename'] = $this->askForFilenames($input, $output);
        $configuration['whitelist'] = $this->askForWhitelistPaths($input, $output);

        if ($doFullConfiguration) {
            $configuration['defaultTimezone'] = $this->askForDefaultTimezone($input, $output);
            $configuration['filepath'] = $this->askForFilepaths($input, $output);
            $configuration['blacklist'] = $this->askForBlacklistPaths($input, $output);
        } else {
            $configuration['defaultTimezone'] = $this->defaultTimezone;
            $configuration['filepath'] = array(
                'classmap' => $this->defaultPath,
                'autoloader' => $this->defaultPathu,
                'configuration' => $this->defaultPath
            );
            $configuration['blacklist'] = $this->defaultBlacklist;
        }

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
        $createAutoloaderFile = ($this->askAndValidate($output, $question, $validator, false, $default) == 'y') ? true : false;

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
        $defaultClassmapFilename = 'autoloader_classmap';
        $defaultAutoloaderFilename = 'generated_autoloader';
        $questionClassmapFilename = '<question>Please enter a name for the classmap file (default is "' . $defaultClassmapFilename . '".' . PHP_EOL .
                'You don\'t have to suffix the file with ".php".</question>';
        $questionAutoloaderFilename = '<question>Please enter a name for the autoloader file (default is "' . $defaultAutoloaderFilename . '".' . PHP_EOL .
                'You don\'t have to suffix the file with ".php".</question>';
        $validator = function ($answer) {
                if (strlen($answer) < 4) {
                    throw new RunTimeException(
                        'The filename should be at least four characters long.'
                    );
                }

                return $answer;
            };

        $classmapFilename = $this->askAndValidate($output, $questionClassmapFilename, $validator, false, $defaultClassmapFilename);
        $autoloaderFilename = $this->askAndValidate($output, $questionAutoloaderFilename, $validator, false, $defaultAutoloaderFilename);

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
        $defaultPath = $this->defaultPath;
        $questionClassmapFilepath = '<question>Please enter the path where you want to store the classmap file (default is "' . $defaultPath . '").</question>';
        $questionAutoloaderFilepath = '<question>Please enter the path where you want to store the autoloader file (default is "' . $defaultPath . '").</question>';
        $questionConfigurationFilepath = '<question>Please enter the path where you want to store the configuration file (default is "' . $defaultPath . '").</question>';
        $validator = function ($answer) {
                if ((!is_dir(realpath($answer)))
                    || (!is_writeable(realpath($answer)))) {
                    throw new RuntimeException(
                        'The path must exists and has to be writeable.'
                    );
                }

                return $answer;
            };

        $classmapFilepath = $this->askAndValidate($output, $questionClassmapFilepath, $validator, false, $defaultPath);
        $autoloaderFilepath = $this->askAndValidate($output, $questionAutoloaderFilepath, $validator, false, $defaultPath);
        $configurationFilepath = $this->askAndValidate($output, $questionConfigurationFilepath, $validator, false, $defaultPath);

        return array(
            'classmap' => $classmapFilepath,
            'autoloader' => $autoloaderFilepath,
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
        $default = '';
        $question = '<question>Enter path you want to blacklist.</question>';
        $blacklist = $this->defaultBlacklist;

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
        $question = '<question>Please enter your default timezone (default is "' . $this->defaultTimezone . '").</question>';
        $validator = function ($answer) {
                if (($answer != '')
                    && (strpos($answer, '/') === false)) {
                    throw new RunTimeException(
                        'The timezone needs at least one "/" in it.'
                    );
                }

                return $answer;
            };

        $defaultTimezone = $this->askAndValidate($output, $question, $validator, false, $this->defaultTimezone);

        return (string) $defaultTimezone;
    }
}
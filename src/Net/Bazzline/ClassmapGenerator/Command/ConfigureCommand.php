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

        $fileName = 'classmap_generator_configuration.php';
        $filePath = getcwd();

        $output->writeln('<info>Writing configuration to "' . $filePath .
            DIRECTORY_SEPARATOR . $fileName . '</info>');
        if ($this->writeConfiguration($configuration, $fileName, $filePath)) {
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
     * @return bool
     * @since 2013-04-21
     */
    private function writeConfiguration(array $data, $fileName, $filePath)
    {
        $writer = new ConfigurationFilewriter();
        $writer->setFilePath($filePath . DIRECTORY_SEPARATOR . $fileName);
        $writer->setFiledata($data);

        return $writer->write();
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
        $answerers = array('y', 'n');

        $createAutoloaderFile = ($dialog->select(
            $output,
            '<question>Should i create a autoloader file?</question>',
            $answerers,
            0
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

        $classmapFilename = $dialog->askAndValidate(
            $output,
            '<question>Please enter a name for the classmap file. You don\'t have to suffix the file with ".php".</question>',
            function ($answer) {
                if (strlen($answer) < 4) {
                    throw new RunTimeException(
                        'The filename should be at least four characters long.'
                    );
                }

                return $answer;
            },
            false,
            'autoloader_classmap'
        );

        $autoloaderFilename = $dialog->askAndValidate(
            $output,
            '<question>Please enter a name for the autoloader file. You don\'t have to suffix the file with ".php".</question>',
            function ($answer) {
                if (strlen($answer) < 4) {
                    throw new RunTimeException(
                        'The filename should be at least four characters long.'
                    );
                }

                return $answer;
            },
            false,
            'generated_autoloader'
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

        $classmapFilepath = $dialog->askAndValidate(
            $output,
            'Please enter the path where you want to store the classmap file.',
            function ($answere) {
                if ((!is_dir($answere))
                    || (!is_writeable($answere))) {
                    throw new RuntimeException(
                        'The path must exists and has to be writeable.'
                    );

                    return $answere;
                }
            },
            false,
            getcwd()
        );

        $autoloaderFilepath = $dialog->askAndValidate(
            $output,
            'Please enter the path where you want to store the autoloader file.',
            function ($answere) {
                if ((!is_dir($answere))
                    || (!is_writeable($answere))) {
                    throw new RuntimeException(
                        'The path must exists and has to be writeable.'
                    );

                    return $answere;
                }
            },
            false,
            getcwd()
        );

        $configurationFilepath = $dialog->askAndValidate(
            $output,
            'Please enter the path where you want to store the configuration file.',
            function ($answere) {
                if ((!is_dir($answere))
                    || (!is_writeable($answere))) {
                    throw new RuntimeException(
                        'The path must exists and has to be writeable.'
                    );

                    return $answere;
                }
            },
            false,
            getcwd()
        );

        return array(
            'classmap' => $classmapFilepath,
            'autloader' => $autoloaderFilepath,
            'configuration' => $configurationFilepath
        );
    }

    private function askForWhitelistPaths(InputInterface $input, OutputInterface $output)
    {
        $whitelist = array();

        while (true) {
            //@TODO ask for whitelist path as long as exit command is triggered
        }

        return $whitelist;
    }

    private function askForBlacklistPaths(InputInterface $input, OutputInterface $output)
    {
        $blacklist = array();

        while (true) {
            //@TODO ask for blacklist path as long as exit command is triggered
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
        $defaultTimezone = $dialog->askAndValidate(
            $output,
            '<question>Please enter your default timezone.</question>',
            function ($answer) {
                if (strpos($answer, '\\') === false) {
                    throw new RunTimeException(
                        'The timezone needs at least one "\\" in it.'
                    );
                }

                return $answer;
            },
            false,
            'Europe/Berlin'
        );

        return (string) $defaultTimezone;
    }
}
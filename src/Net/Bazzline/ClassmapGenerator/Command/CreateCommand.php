<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Net\Bazzline\ClassmapGenerator\Factory\FilepathIteratorFactory;
use Net\Bazzline\ClassmapGenerator\Factory\ClassmapFilewriterFactory;
use Net\Bazzline\ClassmapGenerator\Factory\AutoloaderFilewriterFactory;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class CreateCommand extends CommandAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-04-21
     */
    protected  function configure()
    {
        $this
            ->setName('create')
            ->setDescription('Creates classmap.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Overwrites existing files.')
        ;
    }

    /**
     * @author stev leibelt
     * @since 2013-02-28
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->getApplication()->getConfiguration();
        $classmapWasWritten = false;
        $autoloaderWasWritten = false;

        if (count($configuration) < 1) {
            $output->writeln('<error>Configuration is empty or does not exist.</error>');
            $output->writeln('<error>Try to call "classmap_generator.php configure".</error>');
        } else {
            $autoloaderFilePath = realpath($configuration['filepath']['autoloader']) .
                DIRECTORY_SEPARATOR . $configuration['filename']['autoloader'];
            $classmapFilePath = realpath($configuration['filepath']['classmap']) .
                DIRECTORY_SEPARATOR . $configuration['filename']['classmap'];
            $isForced = $input->getOption('force');

            $filepathIterator = FilepathIteratorFactory::create(
                array(
                    FilepathIteratorFactory::OPTION_BASE_PATH => getcwd(),
                    FilepathIteratorFactory::OPTION_BLACKISTED_DIRECTORIES => $this->generatePaths($configuration['blacklist']),
                    FilepathIteratorFactory::OPTION_WHITELISTED_DIRECTORIES => $this->generatePaths($configuration['whitelist'])
                )
            );

            $classmapFileWriter = ClassmapFilewriterFactory::create(
                array(
                    ClassmapFilewriterFactory::OPTION_FILE_DATA => $filepathIterator->iterate(),
                    ClassmapFilewriterFactory::OPTION_FILE_PATH => $classmapFilePath
                )
            );

            if ($classmapFileWriter->fileExists()) {
                if ($isForced) {
                    $classmapWasWritten = $classmapFileWriter->overwrite();
                } else {
                    $output->writeln('<comment>Classmap exists and overwriting was not forced.</comment>');
                }
            } else {
                $classmapWasWritten = $classmapFileWriter->write();
            }

            if ($configuration['createAutoloaderFile']) {
                $autoloaderFilewriter = AutoloaderFilewriterFactory::create(
                    array(
                        AutoloaderFilewriterFactory::OPTION_FILE_PATH_AUTOLOADER => $autoloaderFilePath,
                        AutoloaderFilewriterFactory::OPTION_FILE_PATH_CLASSMAP => $classmapFilePath
                    )
                );

                if ($autoloaderFilewriter->fileExists()) {
                    if ($isForced) {
                        $autoloaderWasWritten = $autoloaderFilewriter->overwrite();
                    } else {
                        $output->writeln('<comment>Autoloader exists and overwriting was not forced.</comment>');
                    }
                } else {
                    $autoloaderWasWritten = $autoloaderFilewriter->write();
                }
            }

            if ($classmapWasWritten) {
                $output->writeln('<info>Classmap was written to "' .
                    $classmapFilePath . '" .</info>');
            } else {
                $output->writeln('<error>Classmap was not written.</error>');
            }

            if ($autoloaderWasWritten) {
                $output->writeln('<info>Autoloader was written to "' .
                   $autoloaderFilePath . '".</info>');
            } else {
                $output->writeln('<error>Autoloader was not written.</error>');
            }
        }
    }

    /**
     * @author stev leibelt
     * @param array $paths
     * @param string $parentDirectory
     * @return array
     * @since 2013-03-02
     */
    private function generatePaths($paths, $parentDirectory = '')
    {
        $generatedPaths = array();

        if ($parentDirectory !== '') {
            $parentDirectory .= DIRECTORY_SEPARATOR;
        }

        foreach ($paths as $currentPath => $belowPath) {
            $currentWorkingPath = $parentDirectory . $currentPath;
            if (is_array($belowPath)) {
                $generatedPaths = array_merge($generatedPaths, $this->generatePaths($belowPath, $currentWorkingPath));
            } else if ($belowPath !== '*') {
                $generatedPaths[] = $currentWorkingPath . DIRECTORY_SEPARATOR . $belowPath;
            } else {
                $generatedPaths[] = $currentWorkingPath;
            }
        }

        return $generatedPaths;
    }
}
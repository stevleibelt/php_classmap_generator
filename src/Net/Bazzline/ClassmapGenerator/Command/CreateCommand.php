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
        if (count($configuration) < 1) {
            $output->writeln('<error>Configuration is empty or does not exist.</error>');
            $output->writeln('<error>Try to call "classmap_generator.php configure".</error>');
        } else {
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
                    ClassmapFilewriterFactory::OPTION_FILE_PATH => realpath($configuration['filepath']['classmap']) . DIRECTORY_SEPARATOR . $configuration['filename']['classmap']
                )
            );
            if ($classmapFileWriter->fileExists()) {
                if ($isForced) {
                    if ($classmapFileWriter->overwrite()) {
                        $output->writeln('<info>Classmap was written</info>');
                    } else {
                        $output->writeln('<error>Classmap was not written</error>');
                    }
                } else {
                    $output->writeln('<comment>Classmap exists and overwriting was not forced.</comment>');
                }
            } else {
                if ($classmapFileWriter->write()) {
                    $output->writeln('<info>Classmap was written</info>');
                } else {
                    $output->writeln('<error>Classmap was not written</error>');
                }
            }

            if ($configuration['createAutoloaderFile']) {
                $autoloaderFilewriter = AutoloaderFilewriterFactory::create(
                    array(
                        AutoloaderFilewriterFactory::OPTION_FILE_PATH_AUTOLOADER => realpath($configuration['filepath']['autoloader']) . DIRECTORY_SEPARATOR . $configuration['filename']['autoloader'],
                        AutoloaderFilewriterFactory::OPTION_FILE_PATH_CLASSMAP => realpath($configuration['filepath']['classmap']) . DIRECTORY_SEPARATOR . $configuration['filename']['classmap']
                    )
                );

                if ($autoloaderFilewriter->fileExists()) {
                    if ($isForced) {
                        if ($autoloaderFilewriter->overwrite()) {
                            $output->writeln('<info>Autoloader was written.</info>');
                        } else {
                            $output->writeln('<error>Autoloader was not written.</error>');
                        }
                    } else {
                        $output->writeln('<comment>Autoloader exists and overwriting was not forced.</comment>');
                    }
                } else {
                    if ($autoloaderFilewriter->write()) {
                        $output->writeln('<info>Autoloader was written.</info>');
                    } else {
                        $output->writeln('<error>Autoloader was not written.</error>');
                    }
                }
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
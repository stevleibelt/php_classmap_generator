<?php

namespace Net\Bazzline\ClassmapGenerator\Command;

use Net\Bazzline\ClassmapGenerator\Filesystem\Iterate\FilepathIterator;
use Net\Bazzline\ClassmapGenerator\Filesystem\Write\ClassmapFilewriter;
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
            ->setDescription('Creates classmap and autoloader files.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Overwrites existing files.')
            ->addOption('classmap', null, InputOption::VALUE_NONE, 'Create only classmap file.')
            ->addOption('autoloader', null, InputOption::VALUE_NONE, 'Create only autoloader file.')
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
            $autoloaderFilepath = realpath($configuration['filepath']['autoloader']) .
                DIRECTORY_SEPARATOR . $configuration['filename']['autoloader'];
            $classmapFilepath = realpath($configuration['filepath']['classmap']) .
                DIRECTORY_SEPARATOR . $configuration['filename']['classmap'];
            $isForced = $input->getOption('force');
            $onlyCreateClassmapFile = $input->getOption('classmap');
            $onlyCreateAutoloaderFile = $input->getOption('autoloader');

            $filepathIterator = $this->getFilepathIterator($configuration);
            $classmapFileWriter = $this->getClassmapFileWriter($filepathIterator, $classmapFilepath);

            if (!$onlyCreateAutoloaderFile) {
                $classmapWasWritten = $this->writeClassmap($classmapFileWriter, $isForced, $output);

                if ($classmapWasWritten) {
                    $output->writeln('<info>Classmap was written to "' .
                        $classmapFilepath . '" .</info>');
                } else {
                    $output->writeln('<error>Classmap was not written.</error>');
                }
            }

            if (!$onlyCreateClassmapFile
                && $configuration['createAutoloaderFile']) {
                $autoloaderFileWriter = $this->getAutoloaderFileWriter($autoloaderFilepath, $classmapFilepath);
                $autoloaderWasWritten = $this->writeAutoloaderFile($autoloaderFileWriter, $isForced, $output);

                if ($autoloaderWasWritten) {
                    $output->writeln('<info>Autoloader was written to "' .
                        $autoloaderFilepath . '".</info>');
                } else {
                    $output->writeln('<error>Autoloader was not written.</error>');
                }
            }
        }
    }

    /**
     * @author stev leibelt
     * @param $autoloaderFilewriter
     * @param $isForced
     * @param OutputInterface $output
     * @return bool
     * @since 2013-04-24
     */
    private function writeAutoloaderFile($autoloaderFilewriter, $isForced, OutputInterface $output)
    {
        $wasWritten = false;

        if ($autoloaderFilewriter->fileExists()) {
            if ($isForced) {
                $wasWritten = $autoloaderFilewriter->overwrite();
            } else {
                $output->writeln('<comment>Autoloader exists and overwriting was not forced.</comment>');
            }
        } else {
            $wasWritten = $autoloaderFilewriter->write();
        }

        return $wasWritten;
    }

    /**
     * @author stev leibelt
     * @param ClassmapFilewriter $classmapFileWriter
     * @param $isForced
     * @param OutputInterface $output
     * @return bool
     * @since 2013-04-24
     */
    private function writeClassmap(ClassmapFilewriter $classmapFileWriter, $isForced, OutputInterface $output)
    {
        $wasWritten = false;

        if ($classmapFileWriter->fileExists()) {
            if ($isForced) {
                $wasWritten = $classmapFileWriter->overwrite();
            } else {
                $output->writeln('<comment>Classmap exists and overwriting was not forced.</comment>');
            }
        } else {
            $wasWritten = $classmapFileWriter->write();
        }

        return $wasWritten;
    }

    /**
     * @author stev leibelt
     * @param array $configuration
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Iterate\FilepathIterator
     * @since 2013-04-24
     */
    private function getFilepathIterator(array $configuration)
    {
        $filepathIterator = FilepathIteratorFactory::create(
            array(
                FilepathIteratorFactory::OPTION_BASE_PATH => getcwd(),
                FilepathIteratorFactory::OPTION_BLACKISTED_DIRECTORIES => $this->generatePaths($configuration['blacklist']),
                FilepathIteratorFactory::OPTION_WHITELISTED_DIRECTORIES => $this->generatePaths($configuration['whitelist'])
            )
        );

        return $filepathIterator;
    }

    /**
     * @author stev leibelt
     * @param $autoloaderFilepath
     * @param $classmapFilepath
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Write\AutoloaderFilewriter
     * @since 2013-04-24
     */
    private function getAutoloaderFileWriter($autoloaderFilepath, $classmapFilepath)
    {
        $autoloaderFilewriter = AutoloaderFilewriterFactory::create(
            array(
                AutoloaderFilewriterFactory::OPTION_FILE_PATH_AUTOLOADER => $autoloaderFilepath,
                AutoloaderFilewriterFactory::OPTION_FILE_PATH_CLASSMAP => $classmapFilepath
            )
        );

        return $autoloaderFilewriter;
    }

    /**
     * @author stev leibelt
     * @param FilepathIterator $filepathIterator
     * @param $classmapFilepath
     * @return \Net\Bazzline\ClassmapGenerator\Filesystem\Write\ClassmapFilewriter
     * @since 2013-04-24
     */
    private function getClassmapFileWriter(FilepathIterator $filepathIterator, $classmapFilepath)
    {
        $classmapFileWriter = ClassmapFilewriterFactory::create(
            array(
                ClassmapFilewriterFactory::OPTION_FILE_DATA => $filepathIterator->iterate(),
                ClassmapFilewriterFactory::OPTION_FILE_PATH => $classmapFilepath
            )
        );

        return $classmapFileWriter;
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
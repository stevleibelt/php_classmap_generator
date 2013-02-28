<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem;

use Iterator;
use FilterIterator;

/**
 * @author stev leibelt
 * @since 2013-02-28
 */
class PhpFileOrDirectoryFilterIterator extends FilterIterator
{
    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var array
     */
    private $directoryNamesToFilter;

    /**
     * @authors stev leibelt
     * @param \Iterator $iterator
     * @since 2013-02-28
     */
    public function __construct(Iterator $iterator) 
    {
        parent::__construct($iterator);

        $this->setDirectoryNamesToFilter(array('.', '..'));
    }

    /**
     * @author stev leibelt
     * @param array $names
     * @since 2013-02-28
     */
    public function setDirectoryNamesToFilter(array $names)
    {
        $this->directoryNamesToFilter = $names;
    }

    /**
     * Accepts directories or php extended files.
     * 
     * @author stev leibelt
     * @return boolean
     * @since 2013-02-28
     */
    public function accept()
    {
        return (($this->current()->isDir()
                    && !in_array($this->current()->getFilename(), $this->directoryNamesToFilter)) 
                || preg_match('@\.(php|php5)$@i', $this->current()));
    }
}
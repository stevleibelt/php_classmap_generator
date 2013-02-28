<?php

namespace Net\Bazzline\ClassmapGenerator\View;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class ErrorView extends ViewAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    public function render()
    {
        foreach ($this->getData() as $entry) {
            fputs(STDERR, '[error] ' . $entry . PHP_EOL);
        }
    }
}
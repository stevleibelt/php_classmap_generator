<?php

namespace Net\Bazzline\ClassmapGenerator\View;

/**
 * @author stev leibelt
 * @since 2013-02-27
 */
class InfoView extends ViewAbstract
{
    /**
     * @author stev leibelt
     * @since 2013-02-27
     */
    public function render()
    {
        foreach ($this->getData() as $entry) {
            fputs(STDOUT, '[info] ' . $entry . PHP_EOL);
        }
    }
}
<?php
/**
 * @author stev leibelt
 * @since 2013-02-28
 */

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Write;

class ClassmapFilewriter extends FilewriterAbstract
{
    /**
     * Writes content of the file
     *
     * @return boolean
     * @throws \RuntimeException
     * @author stev leibelt
     * @since 2013-02-28
     */
    public function write()
    {
        if ($this->fileExists()) {
            return false;
        }

        $date = date('Y-m-d H:i:s');

        $head = <<<EOC
<?php

/**
* Created with Net\Bazzline\ClassmapGenerator
* @author stev leibelt
* @since $date
*/

return array(
EOC;
        $filedata = '';

        if (count($this->getFiledata()) > 0) {
            foreach ($this->getFiledata() as $fileName => $className) {
                $filedata .= PHP_EOL . '    \'' . $fileName . '\' => \'' . $className . '\',';
            }

            if (strlen($filedata) > 0) {
                $filedata = substr($filedata, 0, -1) . PHP_EOL;
            }
        }

        $bottom = ');';

        return (file_put_contents($this->getFilepath(), $head . $filedata . $bottom) !== false);
    }
}
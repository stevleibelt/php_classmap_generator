<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Write;

/**
 * @author stev leibelt
 * @since 2013-02-28
 */
class ClassmapFilewriter extends FilewriterAbstract
{
    /**
     * @author stev leibelt
     * @return boolean
     * @since 2013-02-28
     * @throws \RuntimeException
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
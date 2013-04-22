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

        if (count($this->getFiledata()) > 0) {
            $head = '<?php' . PHP_EOL .
                '/**' . PHP_EOL .
                '* Created with Net\Bazzline\ClassmapGenerator' . PHP_EOL .
                '* @author stev leibelt' . PHP_EOL .
                '* @since ' . date('Y-m-d H:i:s') . PHP_EOL .
                '*/' . PHP_EOL .
                '' . PHP_EOL .
                'return array(';
            $filedata = '';

            foreach ($this->getFiledata() as $fileName => $className) {
                $filedata .= PHP_EOL . '    \'' . $fileName . '\' => \'' . $className . '\',';
            }

            if (strlen($filedata) > 0) {
                $filedata = substr($filedata, 0, -1) . PHP_EOL;
            }

            $bottom = ');';

            return (file_put_contents($this->getFilepath(), $head . $filedata . $bottom) !== false);
        } else {
            return false;
        }
    }
}
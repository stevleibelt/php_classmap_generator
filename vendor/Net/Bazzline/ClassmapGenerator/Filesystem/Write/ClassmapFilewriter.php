<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Write;

/**
 * @author stev leibelt
 * @since 2013-02-28
 */
class ClassmapFilewriter implements WriterInterface
{
    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var array
     */
    private $filedata;

    /**
     * @author stev leibelt
     * @since 2013-02-28
     * @var string
     */
    private $filepath;

    /**
     * @author stev leibelt
     * @param string $filepath
     * @since 2013-02-28
     */
    public function setFilePath($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * @author stev leibelt
     * @param array $filedata
     * @since 2013-02-28
     */
    public function setFiledata(array $filedata)
    {
        $this->filedata = $filedata;
    }

    /**
     * @author stev leibelt
     * @return boolean
     * @since 2013-02-28
     */
    public function fileExists()
    {
        return file_exists($this->filepath);
    }

    /**
     * @author stev leibelt
     * @return boolean
     * @since 2013-02-28
     * @throws \RuntimeException
     */
    public function overwrite()
    {
        if ($this->fileExists()) {
            unlink($this->filepath);
        }

        return $this->write();
    }

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

        if (count($this->filedata) > 0) {
            $head = '<?php' . PHP_EOL .
                '/**' . PHP_EOL .
                '* Created with Net\Bazzline\ClassmapGenerator' . PHP_EOL .
                '*' . PHP_EOL .
                '* Creationdate ' . date('Y-m-d') . ' ' . date('H:i:s') . PHP_EOL .
                '*/' . PHP_EOL .
                '' . PHP_EOL .
                'return array(';
            $filedata = '';

            foreach ($this->filedata as $fileName => $className) {
                $filedata .= PHP_EOL . '    \'' . $fileName . '\' => \'' . $className . '\',';
            }

            if (strlen($filedata) > 0) {
                $filedata = substr($filedata, 0, -1) . PHP_EOL;
            }

            $bottom = ');';

            return (file_put_contents($this->filepath, $head . $filedata . $bottom) !== false);
        } else {
            return false;
        }
    }
}
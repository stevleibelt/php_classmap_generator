<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem;

/**
 * @author stev leibelt
 * @since 2013-02-28
 */
class ClassmapFilewriter
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
    public function setClassmapFilepath($filepath)
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
     */
    public function write()
    {
        if ($this->fileExists()) {
            return false;
        }

        $data = '<?php' . PHP_EOL .
            '/**' . PHP_EOL .
            '* Created with Net\Bazzline\ClassmapGenerator' . PHP_EOL .
            '*' . PHP_EOL .
            '* Creationdate ' . date('Y-m-d') . ' ' . date('H:i:s') . PHP_EOL .
            '*/' . PHP_EOL .
            '' . PHP_EOL .
            'return array(';

        foreach ($this->filedata as $fileName => $className) {
            $data .= PHP_EOL . '    \'' . $fileName . '\' => \'' . $className . '\',';
        }
        $data = substr($data, 0, -1) . PHP_EOL;
        $data .= ');';

        return (file_put_contents($this->filepath, $data) !== false);
    }
}
<?php

namespace Net\Bazzline\ClassmapGenerator\View;

/**
 * @author stev leibelt
 * @since 2013-02-28
 */
class ClassmapFileView extends ViewAbstract
{
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
     * @since 2013-02-28
     */
    public function render()
    {
        $data = '<?php' . PHP_EOL . 
            '/**' . PHP_EOL . 
            '* Created with Net\Bazzline\ClassmapGenerator' . PHP_EOL . 
            '*' . PHP_EOL . 
            '* Creationdate ' . date('Y-m-d') . ' ' . date('H:i:s') . PHP_EOL . 
            '*/' . PHP_EOL . 
            '' . PHP_EOL . 
            'return array(';

        foreach ($this->getData() as $fileName => $className) {
            $data .= PHP_EOL . '    \'' . $fileName . '\' => \'' . $className . '\',';
        }
        $data = substr($data, 0, -1) . PHP_EOL;
        $data .= ');';

        file_put_contents($this->filepath, $data);
    }
}
<?php

namespace Net\Bazzline\ClassmapGenerator\Filesystem\Write;

/**
 * @author stev leibelt
 * @since 2013-04-21
 */
class ConfigurationFilewriter extends FilewriterAbstract
{
    /**
     * @author stev leibelt
     * @return bool
     * @since 2013-04-21
     */
    public function write()
    {
        if ($this->fileExists()) {
            return false;
        }

        $date = date('Y-m-d H:i:s');
        $createAutoloaderFile = $this->getDataValue('createAutoloaderFile', false);
        $defaultTimezone = $this->getDataValue('defaultTimezone', 'Europe/Berlin');
        $data = <<<EOC
<?php
/**
 * Configuration file for classmap generator
 * @since $date
 */

return array (
    'net_bazzline' => array (
        'createAutoloaderFile' => $createAutoloaderFile,
        'defaultTimezone' => $defaultTimezone,
        'filename' => array (
EOC;
        foreach ($this->getDataValue('filename', array()) as $keyname => $filename) {
            $data .= <<<EOC
            '$keyname' => '$filename',
EOC;
        }

        $data .= <<<EOC
        ),
        'filepath' => array (
EOC;
        foreach ($this->getDataValue('filepath', array()) as $keyname => $path) {
            $data .= <<<EOC
            '$keyname' => '$path',
EOC;
        }

        $data .= <<<EOC
        ),
        'blacklist' => array (
EOC;
        foreach ($this->getDataValue('blacklist', array()) as $path) {
            $data .= <<<EOC
            '$path' => '*',
EOC;
        }

            $data .= <<<EOC
        ),
        'whitelist' => array (
EOC;
        foreach ($this->getDataValue('whitelist', array()) as $path) {
            $data .= <<<EOC
            '$path' => '*',
EOC;
        }

        $data .= <<<EOC
        )
    )
);
EOC;

        return (file_put_contents($this->getFilepath(), $data) !== false);
    }

    /**
     * @author stev leibelt
     * @param string $keyname
     * @param null $default
     * @return null
     * @since 2013-04-21
     */
    private function getDataValue($keyname, $default = null)
    {
        $data = $this->getFiledata();

        return ((isset($data[$keyname]))) ? $data[$keyname] : $default;
    }
}

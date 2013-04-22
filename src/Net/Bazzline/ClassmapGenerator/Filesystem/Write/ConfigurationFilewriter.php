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
 * @author stev leibelt
 * @since $date
 */

return array (
    'net_bazzline' => array (
        'createAutoloaderFile' => $createAutoloaderFile,
        'defaultTimezone' => '$defaultTimezone',
        'filename' => array (
EOC;
        $filenames = $this->getDataValue('filename', array());
        $numberOfFilenames = count($filenames);
        $currentNumberOfFilename = 0;

        if ($numberOfFilenames > 0) {
            foreach ($filenames as $keyname => $filename) {
                $lineEnding = (++$currentNumberOfFilename < $numberOfFilenames) ? ',' : '';
                $data .= PHP_EOL . <<<EOC
            '$keyname' => '$filename'$lineEnding
EOC;
            }
        }

        $data .= PHP_EOL . <<<EOC
        ),
        'filepath' => array (
EOC;
        $filepaths = $this->getDataValue('filepath', array());
        $numberOfFilepaths = count($filepaths);
        $currentNumberOfFilepath = 0;

        if ($numberOfFilepaths > 0) {
            foreach ($filepaths as $keyname => $path) {
                $lineEnding = (++$currentNumberOfFilepath < $numberOfFilepaths) ? ',' : '';
                $data .= PHP_EOL . <<<EOC
            '$keyname' => '$path'$lineEnding
EOC;
            }
        }

        $data .= PHP_EOL . <<<EOC
        ),
        'blacklist' => array (
EOC;
        $blacklistItems = $this->getDataValue('blacklist', array());
        $numberOfBlacklistedItems = count($blacklistItems);
        $currentNumberOfBlacklistedItem = 0;

        if ($numberOfBlacklistedItems > 0) {
            foreach ($blacklistItems as $path) {
                $lineEnding = (++$currentNumberOfBlacklistedItem < $numberOfBlacklistedItems) ? ',' : '';
                $data .= PHP_EOL . <<<EOC
            '$path' => '*'$lineEnding
EOC;
            }
        }

            $data .= PHP_EOL . <<<EOC
        ),
        'whitelist' => array (
EOC;
        $whitelistedItems = $this->getDataValue('whitelist', array());
        $numberOfWhitelistedItems = count($whitelistedItems);
        $currentNumberOfWhitelistedItem = 0;

        if ($numberOfBlacklistedItems > 0) {
            foreach ($whitelistedItems as $path) {
                $lineEnding = (++$currentNumberOfWhitelistedItem < $numberOfWhitelistedItems) ? ',' : '';
                $data .= PHP_EOL . <<<EOC
            '$path' => '*'$lineEnding
EOC;
            }
        }

        $data .= PHP_EOL . <<<EOC
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

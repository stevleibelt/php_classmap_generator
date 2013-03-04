php_classmap_generator
======================

php classmap and autoloader generator

Manual
==============
NAME   
        php - classmap generator for psr-2 based php projects   

SYNOPSIS   
        index.php [OPTION]   

DESCRIPTION 
        Creates classmap by iterating over project directories. 

        Following options are available. 

        create
            Creates classmap if no classmap file exists.
        force
            Creates classmap even if classmap file exists.
        configtest
            Tests configuration file.
        help
            Print this manual.
AUTHOR  
        Written by Stev Leibelt  

REPORTING BUGS  
        artodeto@arcor.de  

SEE ALSO  
        artodeto.bazzline.net  

Adapt Configuration
===================

The 'classmap_generator_configuration.php' file provides following options. All paths are relative to the place where you called the index.php.

It can overwrite all available entries in the original configuration.php.

```php
return = array(
    'createAutoloaderFile' => false,  //boolean - create autloader file or not
    'name' => array(
        'classmap' => 'net_bazzline_classmap_generator_autoloader_classmap.php', //string - name of the classmap file
        'autoloader' => 'net_bazzline_classmap_generator_autoloader.php' //string - name of the autoloader file
    ),
    'path' => array(
        'whitelist' => array(
            'vendor' => '*' //example how to whitelist all directories below vendor
        ),
        'blacklist' => array(
            '.' => '*',
            '..' => '*',
            'data' => '*',
            '.git' => '*',
            'nbproject' => '*',
            'install' => '*'
        )
    ),
    'defaultTimezone' => 'Europe/Berlin' //if no timezone is set
);
```
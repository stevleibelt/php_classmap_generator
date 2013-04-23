php_classmap_generator
======================

php classmap and autoloader generator

Manual
======
NAME
        php - classmap generator for psr-2 based php projects
SYNOPSIS
        index.php [OPTION]
DESCRIPTION
        Creates classmap by iterating over project directories.

        Following options are available.

        create
            Creates classmap if no classmap file exists.
        create --force
            Creates classmap even if file exists.
        create --classmap
            Creates only classmap file.
        create --autoloader
            Creates only autoloader file.
        configure
            Create the configuration file.
        configure --full
            Create a full configuration file (generally not needed).
        manual
            Print this manual.
AUTHOR
        Written by Stev Leibelt

REPORTING BUGS
        artodeto@arcor.de

SEE ALSO
        artodeto.bazzline.net

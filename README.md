php_classmap_generator
======================

php classmap and autoloader generator

Manual
======
Manual
==============
NAME
        universal php classmap generator (especially for non psr-0 projects)
SYNOPSIS
        net_bazzline_classmap_generator.php command [OPTION]
DESCRIPTION
        Creates classmap by iterating over project directories.

        Following options are available.

        create
            Creates classmap if no classmap file exists.
        create --force
        create -f
            Creates classmap even if file exists.
        create --classmap
        create -c
            Creates only classmap file.
        create --autoloader
        create -a
            Creates only autoloader file.
        configure
            Create the configuration file.
        configure --detail
        configure -d
            Create a full configuration file (generally not needed).
        manual
            Print this manual.
AUTHOR
        Written by Stev Leibelt

REPORTING BUGS
        artodeto@arcor.de

SEE ALSO
        artodeto.bazzline.ne

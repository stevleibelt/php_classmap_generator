# PLEASE NOTE, THIS PROJECT IS NO LONGER BEING MAINTAINED

I still like the idea but there is currently no use case to develop it anymore.

# Classmap generator for PHP

php classmap and autoloader generator

# Manual

```
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
```

# History

* upcomming
    * changed to bazzline/php_component_cli_arguments
    * changed to bazzline/php_component_command
    * splitted into components
    * renamend "bin/net_bazzline_classmap_generator.php" to "bin/net_bazzline_classmap_generator"
* [v1.4.1](https://github.com/stevleibelt/php_classmap_generator/tree/v1.4.1)
    * changed licence to lgpl v3
* [v1.4](https://github.com/stevleibelt/php_classmap_generator/tree/v1.4)
    * implemented usage of symfony command
    * implemented validation
* [v0.9](https://github.com/stevleibelt/php_classmap_generator/tree/v0.9)
    * initial commit

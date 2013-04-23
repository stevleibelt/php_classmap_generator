php_classmap_generator
======================

php classmap and autoloader generator

Manual
==============
NAME   
        php - classmap generator for all php files containing one or multiple interface|abstract class|class decleration.

SYNOPSIS   
        bin/classmap_generator.php [OPTION]   

DESCRIPTION 
        Creates classmap by iterating over configured project directories. 
        Can have multiple configuration files.

        Following options are available. 

        create
            Creates classmap if no classmap file exists.
        create --force
            Creates classmap even if classmap file exists.
        configure
            Creates the configuration file.
        configure --full
            Creates a full configuration file (generally not needed).
        help
            Print this manual.
AUTHOR  
        Written by Stev Leibelt  

REPORTING BUGS  
        artodeto@arcor.de  

SEE ALSO  
        artodeto.bazzline.net  

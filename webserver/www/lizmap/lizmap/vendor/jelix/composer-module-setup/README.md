# composer-module-setup

A plugin for Composer to declare automatically jelix modules into a jelix application

For Jelix 1.6.9 and higher.

Authors who provide their modules via Composer, should declare directories 
containing modules or plugins. It will avoid the developer to declare them 
into his application.init.php (Jelix 1.7) or in the configuration (Jelix 1.6.9+).

## installation

In the composer.json of your application, declare the plugin

```json
{
    "require": {
        "jelix/composer-module-setup": "^1.0.0"
    }
}
```

## Declaring modules and plugins into a package

Author who provide their modules via Composer, should declare directories containing modules
or plugins. It will avoid the developer to declare them into his application.init.php.

To declare them, he should add informations into the extra/jelix object in composer.json:

```json
{
    "extra": {
        "jelix": {
        }
    }
}
```

In this object, he can add three type of informations:

- `modules-dir`, an array containing directories where modules can be found.
  These paths will be added to the jelix configuration parameter `modulesPath`.
- `plugins-dir`, an array containing directories where plugins can be found.
  These paths will be added to the jelix configuration parameter `pluginsPath`.
- `modules`, an array containing modules directories.
  These paths will be added into the `modules` section of the jelix configuration
   as `<module>.path=<path>`.

For instances, in the repository, if modules are in a "modules/" directory, the 
author should add these informations into his composer.json:

```json
{
    "extra": {
        "jelix": {
            "modules-dir" : [
                "modules/"
            ],
            "plugins-dir" : [
                "plugins/"
            ],
            "modules" : [
                "mymoduleaaa/",
                "mymodulebbb/"
            ]
        }
    }
}
```

## Declaring modules and plugins at the application level

Application developers could declare also their modules and plugins in the same way, in
the composer.json of the application:

```json
{
    "require": {
        "jelix/composer-module-setup": "^1.0.0"
    },
    "extra": {
        "jelix": {
            "modules-dir" : [
                "myapp/modules/"
            ],
            "plugins-dir" : [
                "myapp/plugins/"
            ],
            "modules" : [
                 "mainmodule/"
            ]
        }
    }
}
```


## Enable modules on entrypoints automatically (jelix 1.6 only)

In module packages, you can indicate on which entrypoint the module should be enabled.

```json
{
    "extra": {
        "jelix": {
            "autoconfig-access-16" : {
                "__any_app" : {
                    "<modulename>": {
                        "__global": 1
                    }
                },
                "app/identifiant1" : {
                    "<modulename>": {
                        "__global": 1,
                        "index" : 2,
                        "admin" : 1   
                    }
                }
            }
        }
    }
}
```

`app/identifiant1` must be the application id that is indicated into the `project.xml`
file ( `id` attribute of the `<info>` element).

In the composer.json of the application, you can also indicate the same informations
for each modules, when a module does not provide an `"autoconfig-access-16"` configuration 

```json
{
    "extra": {
        "jelix": {
            "modules-autoconfig-access-16" : {
                "package/name1" : {
                    "<modulename>": {
                        "__global": 1,
                        "index" : 2,
                        "admin" : 1   
                    }
                },
                "package/name2" : {
                    "<modulename>": {
                        "__global": 1,
                        "index" : 2,
                        "admin" : 1   
                    }
                }
            }
        }
    }
}
```

If the package has already an `"autoconfig-access-16"` configuration, this
`modules-autoconfig-access-16` configuration has priority over it.

## In Jelix 1.7 and higher

In your application.init.php, you should include the jelix_app_path.php:

```php
<?php

require (__DIR__.'/vendor/autoload.php');

jApp::initPaths(
    __DIR__.'/'
    //__DIR__.'/www/',
    //__DIR__.'/var/',
    //__DIR__.'/var/log/',
    //__DIR__.'/var/config/',
    //__DIR__.'/scripts/'
);
jApp::setTempBasePath(realpath(__DIR__.'/temp').'/');

require (__DIR__.'/vendor/jelix_app_path.php');

```

Remember: in Jelix 1.7 and higher, declaring modules and plugins in the modulesPath/pluginsPath
parameter in the configuration file is not supported anymore.

## In Jelix 1.6.x equal or higher than 1.6.9

The composer plugin declares automatically modules and plugins directory into 
the localconfig.ini.php file or into the mainconfig.ini.php file, 
in `modulesPath` and `pluginsPath` properties, and also in the `modules` section.

However, at the application level, the composer.json may also content the path
to the application directory (the directory containing the project.xml etc), and
the path to the var/config directory.


So if the directory containing the composer.json file is not the application 
directory and/or if the var/config is not in the application directory, you must 
set these paths into `app-dir` and `var-config-dir`. Path should be
relative to the composer.json directory, or can be absolute.

```json
{
    "require": {
        "jelix/composer-module-setup": "^1.0.0"
    },
    "extra": {
        "jelix": {
            "app-dir" : "myapp/",
            "var-config-dir" : "/var/lib/myapp/config/",
            "modules-dir" : []
        }
    }
}
```

You can also indicate if you want that the plugin modify localconfig.ini.php 
or mainconfig.ini.php. By default it is localconfig.ini.php. Indicate the
configuration filename into `config-file-16`:


```json
{
    "require": {
        "jelix/composer-module-setup": "^1.0.0"
    },
    "extra": {
        "jelix": {
            "app-dir" : "myapp/",
            "var-config-dir" : "/var/lib/myapp/config/",
            "modules-dir" : [],
            "config-file-16": "mainconfig.ini.php"
        }
    }
}
```

## debugging the plugin

Set an environnement variable `JELIX_DEBUG_COMPOSER` to `true` or create an 
empty file named `JELIX_DEBUG_COMPOSER` into the vendor directory.

After launching Composer, you will have a file `jelix_debug.log` into
the vendor directory.

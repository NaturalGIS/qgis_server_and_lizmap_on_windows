Some utilities to manipulate files, directories and paths.

# Installation

You can install it from Composer. In your project:

```
composer require "jelix/file-utilities"
```

# Features

The `File` class allows you to read and write file contents. `write()` method
allows to change the content of an existing file by not writing into it
directly, avoiding write lock.

The `Directory` class allows to delete recursively content of a directory,
and even to do it without deleteting some specific files.

For both `File` and `Directory` classes, chmod values can be set globally or
specifically, when they create a file or a directory.

The `Path` class allows to cleanup a path, to resolve a path against an other, or
to get the shortest path between to path. All of its methods works on path string
and do not rely on the file system. So paths can represent files/directories that
do not exist.


# History

Most of methods of first version of the classes come originally from the `jFile`
class of Jelix 1.6. http://jelix.org

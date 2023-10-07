Some classes to read and write properties files. 

Properties files are like Java Properties file. The implemented format is using
to store locales for an application made with [Jelix](https://jelix.org), 
a PHP Framework.

installation
============

The library is compatible from PHP 5.6 to PHP 8.1.

You can install it from Composer. In your project:

```
composer require "jelix/properties-file"
```


Usage
=====

You have two classes: `Properties` which is a container for key/value pairs,
a `Reader` to  parse a properties file and a `Writer` to write properties into
a file.

```php

use \Jelix\PropertiesFile\Properties;
use \Jelix\PropertiesFile\Parser;
use \Jelix\PropertiesFile\Writer;

$properties = new Properties();

$reader = new Parser();
$reader->parseFromFile('file.properties', $properties);

$value = $properties->get('a_key');
$value = $properties['a_key'];

$properties->set('a_key', 'new_value');
$properties['a_key'] = 'new_value';

$writer = new Writer();
$writer->writeToFile($properties, 'file.properties');

// with a limit of line length (default is 120)
$writer->writeToFile($properties, 'file.properties', 
                    array("lineLength"=>80));

```

Options for the writer: 

- `lineLength`: maximum length of a line. If the string length is higher, it will be splitted. (default: 120)
- `cutOnlyAtSpace`: to cut lines where there is space, not on the middle of a word (default: true) 
- `spaceAroundEqual`: to add or not space around the equal sign (boolean, default: true)
- `headerComment`: to add comment as header (string, default: empty string)
- `removeTrailingSpace`: to remove trailing space on values (boolean, default: false)
- `encoding`: encoding of values to write. (string, default: "UTF-8")

History
=======

The parser is based on a class, jBundle coming from the [Jelix Framework](http://jelix.org)
until Jelix 1.6, and has been released in 2018 into a separate repository as Jelix\PropertiesFile\Parser.

Format
======

The file content structure is quite simple. It's basically a `key=string`
structure, with some improvements. 

You can't use double and single quotes to delimit your strings, new lines do this.

Keys can contain characters `a` to `z` (lowercase/uppercase), numbers and
characters `_`, `-`, `.`.

Here is an example of file:

```ini
title.offlineElements = elements to check
title.onlineElements = online elements
buttons.save = Save
buttons.ok=Ok
```

Multi line
----------

If the text is long and you want to write it in several lines, you can type an
anti-slash `\` at the end of each line (excepted the last one of the text), to tell
the parser to continue reading the translated string.

```ini
intro=this is a very very\
long text in\
several lines
message=this is a regular line
```

However, it doesn't insert a line break in the displayed string. If you want to
insert a real line break, use `\n` or `\r` (`\r\n` on windows, `\n` on linux, 
`\r` on macs):

```ini
intro=this is a very very \
long text in\nseveral lines, but in\n one line\nin the source
```

Comments
--------

You can also put some comments. They have to begin with a `#`. When the parser sees
`#`, the rest of the line is ignored. A comment can be at the beginning of a line,
or in the middle of a line, or at the end of the line. If you want to use a `#` in a
value, you have to escape it with an anti-slash: `\#`.

Whitespaces
-----------

Whitespaces before and after a value are ignored. If you want to put a value equal
to a space, you have to use `\s`.

```ini
nospace= #this is using a regular space
space= \s#this is using a \s space
```

The value of `space` will be `' '`, and the value of `nospace`, an empty string.

You can also use `\S` to insert an 'unbreakable' space.

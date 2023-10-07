Some classes to read and modify ini files by preserving comments, empty lines.

They supports sections and array values. You can also merge sections, merge two
ini files, and rename some values or sections.

# installation

You can install it from Composer. In your project:

```
composer require "jelix/inifile"
```

# Usage

The ```\Jelix\IniFile\IniModifier``` class allows to read an ini file, to modify its
content, and save it by preserving its comments and empty lines.

Don't use this class to just read content. Use instead ```\Jelix\IniFile\Util``` or
```parse_ini_file()``` for this purpose, it's more efficient and performant.


```php
$ini = new \Jelix\IniFile\IniModifier('myfile.ini');

// setting a parameter.  (section_name is optional)
$ini->setValue('parameter_name', 'value', 'section_name');

// retrieve a parameter value. (section_name is optional)
$val = $ini->getValue('parameter_name', 'section_name');

// remove a parameter
$ini->removeValue('parameter_name', 'section_name');


// save into file

$ini->save();
$ini->saveAs('otherfile.ini');

// importing an ini file into an other
$ini2 = new \Jelix\IniFile\IniModifier('myfile2.ini');
$ini->import($ini2);
$ini->save();

// merging two section: merge sectionSource into sectionTarget and then 
// sectionSource is removed
$ini->mergeSection('sectionSource', 'sectionTarget');

```

It supports also array values (indexed or associative) like :

```
foo[]=bar
foo[]=baz
assoc[key1]=car
assoc[otherkey]=bus
```

Then in PHP:

```
$ini = new \Jelix\IniFile\IniModifier('myfile.ini');

$val = $ini->getValue('foo'); // array('bar', 'baz');
$val = $ini->getValue('assoc'); // array('key1'=>'car', 'otherkey'=>'bus');

$ini->setValue('foo', 'other value', 0, '');
$val = $ini->getValue('foo'); // array('bar', 'baz', 'other value');

$ini->setValue('foo', 'five', 0, 5);
$val = $ini->getValue('foo'); // array('bar', 'baz', 'other value', 5 => 'five');


$ini->setValue('assoc', 'other value', 0, 'ov');
$val = $ini->getValue('assoc'); // array('key1'=>'car', 'otherkey'=>'bus', 'ov'=>'other value');
```

After saving, the ini content is:

```
foo[]=bar
foo[]=baz
assoc[key1]=car
assoc[otherkey]=bus

foo[]="other value"
foo[]=five
assoc[ov]="other value"
```

Note: the result can be parsed by `parse_ini_file()`.


See the class to learn about other methods and options.

The ```\Jelix\IniFile\MultiIniModifier``` allows to load two ini files at the same time,
where the second one "overrides" values of the first one.

The ```\Jelix\IniFile\IniModifierArray``` allows to load several files at the 
same time, and to manage their values as if files were merged.

The ```\Jelix\IniFile\Util``` contains simple methods to read, write and merge ini files.
These are just wrappers around ```parse_ini_file()```.

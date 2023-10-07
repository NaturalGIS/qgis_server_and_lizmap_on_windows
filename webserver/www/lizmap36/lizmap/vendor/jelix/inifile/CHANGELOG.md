Changelog

3.3.2
=====

Fix the add of an extra `[0]` section, when saving an ini file that was
empty before adding some values.


3.3.1
=====
- Fix compatibility with PHP 8.1

3.3.0
=====

- support of a new flag FORMAT_NO_QUOTES as parameter for `IniModifier::save()`. Allow to generate an ini file without
  quotes in values.
  It can be useful to generate an ini file compatible with some software, like Postgresql.

3.2.5
=====

- Fix setValue of an array item on an existing string value
- Fix return value of `IniModifierArray::isSection()`

3.2.4
=====
- fix `IniReader::getValue()` on associative array values


3.2.3
=====

- Fix ini merge when base value is a string and new value is an array

3.2.2
=====

- Fix `IniModifier::setValue()` when existing value and new values are ini booleans
  or php boolean. In some case the ini content is not modified even if
  booleans are different.

3.2.1
=====

- Fix compatibility with PHP 7.3

3.2.0
=====

- `Utils::read()` and `Utils::readAndMergeObject()` are now using the
  `INI_SCANNER_TYPED` mode of `parse_ini_file`.
- Fix parsing to match results given by `parse_ini_file` with the `INI_SCANNER_TYPED` mode.
  values `true`, `on`, `yes` are converted to `true`, values `false`, `no`, `off`
  and `none` are converted to `false`.

3.1.1
=====

- Fix parsing of section names having strange characters or ";" character

3.1.0
=====

- new parameter in Util::readAndMergeObject() and Utils::mergeIniObjectContents()
  to indicate a list of sections and top level parameters that will not be merged
  into the initial content

3.0.0
======

- IniReaderInterface declare new methods: isEmpty(), getFileName(), isSection(),
  getSectionList().
- New IniModifierReadOnly object. This is a decorator to any IniModifierInterface
  or IniReaderInterface, to expose only IniReaderInterface methods. So an
  IniModifierInterface object becomes read only.

2.5.0
=====

- New method IniModifier::removeSection()
- Fix: modification flag was triggered although there was no changes

2.4.2
=====

- Fix: jIniFile: chmod fix
- Fix serialization: false was not serialized correctly

2.4.1
=====

- fix: IniModifierArray should accept IniReaderInterface objects


2.4.0
=====

- new IniReader object, to read only files, to have "read only modifiers" in IniModifierArray
- Exceptions are now Exceptions from the library, IniInvalidArgumentException and IniException
- IniModifier does not require anymore an existing file, and can create a new
  file with a given content.

2.3.0
=====

- New IniModifierArray object. It allows to work on a list of IniModifier objects as the same time

2.2.1
=====

- setValue accepts now an array as value

2.2
===

- Fix `Util.mergeIniObjectContents()` and `Util::readAndMergeObject()`
  about the merge of array values with integer keys.
- New flags for these methods have been added to change the behavior of merge

2.1
===

- Support of most of word characters in names
- Support of associative arrays like

```
foo[mykey]=bar
foo[otherkey]=baz
```

- fix merge of array values during import
- fix renaming of array values


2.0
===

- IniModifier and MultiInitModifier share the same interface
- No more parameter '$onMaster' on methods. Replace by methods
  named `<something>OnMaster()`.

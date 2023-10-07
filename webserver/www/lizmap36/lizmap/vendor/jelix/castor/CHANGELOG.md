Changelog
=========

1.1.1
-----

Fix compatibility with PHP 8.1

1.1.0
-----

- support of auto-escape, with a new tag `{! autoescape !}`. A new `raw` modifier allows to not escape
  automatically a variable.
- Support of macro, with new tags:
  - `{macro}` to declare a block of a macro
  - a function `{usemacro}`
  - some tests `{ifdefinedmacro}` and `{ifundefinedmacro}`
- Support of `{set}`, an alias for `{assign}`
- Support of `{verbatim}`, an alias for `{literal}`
- Support of a new syntax for comments, `{# .. #}`
- Add `json_encode` modifier
- Fix some exceptions
- A little code cleanup
- Tested with PHP 8.0


1.0.1
-----

- Fix compatibility with PHP 7.0 : remove the use of the `T_CHARACTER` constant, into the compiler.


1.0.0
------

First version of Castor, which is the result of the extraction of jTpl from the
framework Jelix.
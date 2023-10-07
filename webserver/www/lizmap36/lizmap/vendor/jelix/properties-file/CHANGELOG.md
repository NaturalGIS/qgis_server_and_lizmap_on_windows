Changelog
==========

1.2.3 (2022-06-29)
------------------

Fix the use of the deprecated function `utf8_encode` for PHP 8.1

1.2.2 (2022-03-15)
------------------

Fix an issue with PHP 8.1

1.2.1 (2019-07-31)
------------------

- fix split of long line and support of encoding

1.2 (2019-07-29)
----------------

- new option for the writer: `cutOnlyAtSpace`.
  Set it to false to cut line by ignoring words


1.1 (2018-10-12)
----------------

- new options for the writer:
    - `spaceAroundEqual`: to add or not space around the equal sign (boolean, default: true)
    - `headerComment`: to add comment as header (string, default: empty string)
    - `removeTrailingSpace`: to remove trailing space on values (boolean, default: false)

1.0 (2018-10-11)
----------------

Initial release


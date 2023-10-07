jCommunity is set of modules to add "community" features to a web site made with the [Jelix framework](http://jelix.org).

It provides:

- user registration: account creation, with confirmation by email and activation key. The form contains a captcha.
- users can choose their password
- possibility to ask a new password when the user has loose his password (again, confirmation by email and activation key)
- Profile editing
- login/logout form
- many new events in controllers, to allow you to do processing at each step
  of the registration and other actions, so your own module can verify or
  doing additional things.
- notification messages with jMessage
- a specific form for jauthdb_admin is provided


Installation
------------

The current version works only with Jelix 1.6.24+ and 1.7.

See INSTALL.md or [the wiki](https://github.com/jelix/jcommunity-module/wiki/installation)

To contribute and test, read the test/README.md file.

Localization
------------

To help to translate the module, go to Transifex: https://www.transifex.com/3liz-1/jelix/modules--jcommunitypot/.
No PR for localization will be accepted.

To download locales, go to https://download.jelix.org/jelix/langpacks/jcommunity/ . 


Others documentation
---------------------

On [the project web site](https://github.com/jelix/jcommunity-module/wiki/):

* [Installation](https://github.com/jelix/jcommunity-module/wiki/installation)
* [Extending jcommunity](https://github.com/jelix/jcommunity-module/wiki/extending_jcommunity)
* [How to contribute](https://github.com/jelix/jcommunity-module/wiki/contribute)


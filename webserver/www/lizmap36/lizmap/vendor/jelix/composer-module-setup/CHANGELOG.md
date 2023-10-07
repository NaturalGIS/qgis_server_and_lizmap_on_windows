Version 1.1.0
=============

- new method `Jelix\ComposerPlugin\JelixPackageParameters::getModules()`, returning the list of Jelix modules embedded into the package
- new class `Jelix\ComposerPlugin\PackagesInformationFile`, to retrieve
  information about all Jelix packages from any package.


Version 1.0.12
==============

Fix Jelix 1.7 setup when no app exists yet.

Version 1.0.11
===============

Fix Jelix 1.7 configurator: the configuration parameters `<module>.path` set
by the Jelix 1.6 configurator should be removed.

Version 1.0.10
==============

Fix an error when updating the packages, whereas there is no application yet.

Version 1.0.9
=============

Fix previous version: it did not use the new internal api `isJelix16()` :-/

Version 1.0.8
=============

Possibility to force the target jelix version for migration

When migrating an app from Jelix 1.6 to Jelix 1.7, the application is still
with the files of Jelix 1.6. So the setup is not done with Jelix 1.7.
Specifying the target allow to launch the setup for the right Jelix version.

Version 1.0.7
=============

Fix: do not use SetupJelix16, when the plugin is used in a secondary composer.json

If the composer.json is not the main application composer.json, the plugin should verify on which Jelix version is
based the application, with a better method than checking jelix packages, as into
a secondary composer.json don't load the jelix packages.


Version 1.0.6
=============

- Fix: do not check the file declared into `config-file-16`, as it may not exists for Jelix 1.7

Version 1.0.5
=============

- Fix path into jelix_modules_infos.json, when there are some file links
- Improve message logs

Version 1.0.4
=============

- New feature: possibility to have a log file of what did the plugin.
  To generate logs, set the environment variable `JELIX_DEBUG_COMPOSER=true`
- Fix access setup on an undefined default entrypoint
- Fix: when removing a module, some infos still remained into jelix app config

Version 1.0.3
=============

- Fix Jelix 1.6 configuration: activation of a module on an entrypoint should
  be removed if a new version of the module does not use anymore the entry-point

Version 1.0.2
=============

- Fix Jelix 1.6 configuration: url engine configuration was missing

Version 1.0.1
=============

- Fix: Jelix 1.6 configuration should be updated when a package is removed

Version 1.0.0
=============

- Compatibility with Composer 2.0


Version 0.7.0
=============

- Support of automatic setup of module access in the configuration for Jelix 1.6.
  See `autoconfig-access-16` and `modules-autoconfig-access-16` parameters.


Version 0.6.1
=============

- Fix installation of modules when the package name contains upper case letters

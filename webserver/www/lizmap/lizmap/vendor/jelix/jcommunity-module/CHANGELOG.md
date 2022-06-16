Version 1.3.14
==============

- Account creation form: add the status field to choice between NEW (an email will be send to the user)
  and VALID (no mail will be sent). Choosing VALID can be usefull when the user authenticate himself with
  other mecanisme than with the password.
- Fix access to administration features like password reset and registration password resending.
  Controllers should check if jAuth authorize the user to change his password.

Version 1.3.13
==============

- Add hook on login form for other auth module. These hooks allow to other modules to add content into the
  login form. See login.tpl
- Fix error display when mailer sent at registration_admin_resend
- Fix links in email for registration, sent by an admin
- Locales for many languages are now available at https://download.jelix.org/jelix/langpacks/jcommunity/


Version 1.3.12
==============

* Fix links in mails for registration or password reset
* New command line script for administrator to delete an account:
```bash

# for Jelix 1.6

php scripts/cmdline.php jcommunity~user:delete <login>

# for Jelix 1.7

php console.php jcommunity:user:delete <login>
```


Version 1.3.11
==============

- Fix registration: new login should be trimmed before saving it into the database

Version 1.3.10
==============

- adds a configuration parameter `noRedirectionOnAuthenticatedLoginPage` in 
  the `jcommunity` section, to disable the new behavior of the login page 
  introduced in 1.3.8. Set `noRedirectionOnAuthenticatedLoginPage=on` to disable
  it.
  
Version 1.3.9
=============

- fix a bad method name into the command to create a user

Version 1.3.8
=============

- fix authentification: when going to the login form as an authenticated user,
  the user should be redirected directly to a page when possible, instead of 
  displaying a message.
  


Version 1.3.7
=============
  
Fix the installer: the json file containing default users can be empty.

New command line scripts for administrator:

- to send a mail to a user with a password reset link
- to change the password of a user
- to create a user.

```bash

# for Jelix 1.6

php scripts/cmdline.php jcommunity~user:changePassword [--force] <login> [<password>]
php scripts/cmdline.php jcommunity~user:resetPassword <login>
php scripts/cmdline.php jcommunity~user:create [--reset] [--admin] [--no-error-if-exist] <login> <email> [<password>]

# for Jelix 1.7

php console.php jcommunity:password:change [--force] <login> [<password>]
php console.php jcommunity:password:reset <login>
php console.php jcommunity:user:create [--reset] [--admin] [--no-error-if-exist] <login> <email> [<password>]
```



Version 1.3.6
=============

- Fix: Check the url to return to, to redirect only to the web site.
- Fix: Profiles page should not be able to be viewed if no rights.
  List of users could be guessed by brut force on the url of
  profile `index.php/users/<login>`
- Fix installer: if nothing can be migrated, default user should be installed

Version 1.3.5
=============

- Fix SQL errors during installation, especially with Postgresql
- Fix bad config variable name: `resetPasswordAdminEnabled` must be `resetAdminPasswordEnabled`
- Fix configuration reading
- Fix display of reset password links in user profile. Buttons to reset passwords 
  should not be shown if password change is not possible.


Version 1.3.4
=============

- Fix upgraders, when some entrypoints have no auth plugin
- Fix error into password reset controller when no form submitted
- Show an error page when the mail sending to reset password has failed
- Show mail error during registration
- Fix: show domain+base path in mail


Version 1.3.3
=============

- Fix compatibility with jauth drivers similar to the db driver

Version 1.3.2
=============

- Fix issue during login:out when enable_after_logout_override is off
- Fix path to modules in composer.json, for Jelix Composer plugin

Version 1.3.1
=============

- Fix installer when a table prefix is used
- Fix installer when the dao for users is overloaded


Version 1.3.0
=============
 
- **New features to reset password** from the jauthdb_admin module
   - New `resetAdminPasswordEnabled` configuration parameter.
     It allows to activate the possibility for an admin user to launch a
     process of a password reset of a user, instead of changing directly
     the password.
   - new page for a user to set a new password after the administration has
     resetted his password.
   - new page for the administrator to reset a password of a user
- **new page to resend validation email** (by the administrator)
- **TTL of the validation is configurable**. Registration key is now valid only two days by default.
- **Fix security issue** about the registration key and password retrieval key.
  There were always the same key for a user.

Developers:

- configurator for Jelix 1.7: interactive configuration of parameters defaultuser & defaultusers
- mails contents are moved to locales properties
- new `urls_registration.xml` file to declare registration admin page separately
 from other pages
- Replace Vagrant by Docker for the test app
- **the `login` field is no more the primary key**, as it causes some issue with
  some database. The `id` is now the primary key.
- Fix installer with default user json file

Version 1.2.2
=============

- Use jAuth::canChangePassword() of Jelix 1.6.21
- compatibility with the upcoming Jelix 1.7.0. Update install scripts for 
   Jelix 1.6.19 and 1.7-beta.4


Version 1.2.1
=============

- add locales for PT
- fix regression in the installer
- support of `liveconfig.ini.php` of Jelix 1.6.18+ to store the encryption key
- Fix localized templates: add default templates

Version 1.2.0
=============

- **New process to request a password**. There is not anymore a form in which the
  user has to indicate a key and a login. The email contain a link having the
  login and the key.
- **New process for registration**.
    It follows "modern" processes for the registration:
    - the form contain the login, email but also the password
    - the email indicate a link, which contain the registration key
      so the user do not need anymore to fill a new form
- **User profile: improve the privacy**.
  A configuration property, publicProperties, allows to
  specify which fields are public, so only these fields
  are shown to any visitor.
- **sends emails in HTML** instead of in plain text.
- **New form allowing user to change its password** when he is authenticated
- Account deletion: ask the password account to confirm
- Improvements in some messages and templates

- Possibility to configure an other form instead of account form.
  In the auth.coord.ini, support of a new parameter, `userform`,
  in the `Db` section. It should contains the selector of the account
  form.
- more integration with jauthdb_admin
- New option `useJAuthDbAdminRights` to take care of jauthdb_admin rights
- New option `accountDestroyEnabled` to allow to delete accounts
- Some features are enabled only if email is well configured

- remove deprecated en_EN locales and en_GB locales
- no more templates for each languages.
- improvements into the installer
- nickname field is now optional
 

Version 1.1.1
=============

- jPref is optional
- fix storage of encryption key for persistant cookie
- fix installation to be more indempotent


Version 1.1.0
=============

To use this version, you need to upgrade Jelix to 1.6.5 minimum.

New features and improvements
------------------------------

- Some improvements have been made to use jCommunity with the master_admin module (with Jelix 1.6.5+ only)
- New install parameters:
   - ```masteradmin```: to indicate we want to use jcommunity for authentication system
   - ```notjcommunitytable```: to indicate to not create the community_users table
   - ```migratejauthdbusers```: to migrate users from a standard jlx_user
     table to a community_users table
- new configuration parameters you can set into a ```jcommunity``` section into
  the application configuration
   - ```loginResponse```: the alias of the html response to use to display the
     main login form.
   - ```registrationEnabled```: to disable or enable the registration feature
   - ```resetPasswordEnabled```: to disable or enable the reset password feature
- you can use jPref to enable/disable registration or password reseting.
- Templates: for unknown users, add a link to return to the login form
- removed the deprecated jcommunity_phorum module

Fixed bugs
----------

- Fix infinite loop after a logout in some cases
- Fix auth_url_return generated into the login form


Version 1.0
===========

- same features as 0.2 and 0.3. 
- Compatibility with Jelix 1.4, 1.5, 1.6


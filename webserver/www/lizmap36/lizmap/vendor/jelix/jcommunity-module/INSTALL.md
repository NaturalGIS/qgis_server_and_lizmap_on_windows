Installation
============

Latest jCommunity version works only with Jelix 1.6.16 and higher.


Get the module from a zip
-------------------------

Download the package from http://download.jelix.org/forge/jcommunity/
and extract it somewhere.

You can then move modules/jcommunity into a module repository of your application,
or declare the directory into the mainconfig.ini.php (for Jelix 1.6), or into
the application.init.php (for Jelix 1.7+).

Get the module from Composer
----------------------------

In your composer.json, in the require section, indicate:

```
"jelix/jcommunity-module": "1.2"
```

After a `composer update`:

1. if you use Jelix 1.6, you can declare jcommunity in the modulePath
   parameter of the configuration. Example:
    ```
    modulePath=(...);app:vendors/jelix/jcommunity-module/modules/
    ```
2. if you use Jelix 1.7 or higher, it is automatically declared.

About the jauth and jauthdb module
----------------------------------

You should do nothing about them. Uninstall and deactivate them. jCommunity provides its
own sql table, and its own dao. jCommunity provides all needed things, with some different
behaviors.

Using jCommunity with master_admin
----------------------------------

jCommunity 1.1+ can be used with the master_admin module. Continue the
installation by reading [the specific manual for this purpose](https://github.com/jelix/jcommunity-module/wiki/master_admin).

Setup with Jelix 1.6
---------------------

In the configuration of the application, activate the module and the auth plugin
for the coordinator:

```
[modules]
jauth.access = 0
jauthdb.access = 0
jcommunity.access = 2
jcommunity.installparam =

[coordplugins]
auth=auth.coord.ini.php
```

Configure also parameters in the mailer section. jCommunity needs to send mail to users.

The installer supports some parameters. You should list them into the
`jcommunity.installparam`, with a semi colon as separator.

- `manualconfig` (1.2+): it does not set authentication parameters into the auth.coord.ini.php
  (so it does not indicate to use the dao and the form of jcommunity etc)
- `migratejauthdbusers` (1.1+): indicate to do migration of jlx_users records 
  into the jCommunity table 
- `defaultuser` : register an "admin" user (password: "admin") into the jCommunity table
  (if there is not the flags `migratejauthdbusers`).
- `defaultusers` : register users listed into a specific JSON file into the jCommunity table
  (if there is not the flags `migratejauthdbusers`, and it replaces `defaultuser`). 
  `defaultusers=mymodule~defaultusers.json`. So if your table contains fields
  that should contain default values, you have to provide your own json file.
  The JSON file should be in the install/ directory of the given module.
- `masteradmin` (1.1+): indicate that jcommunity is used for master_admin module.
  see [the dedicated chapter](https://github.com/jelix/jcommunity-module/wiki/master_admin)

Note: the jCommunity table is the table indicated into the dao set into auth.coord.ini.php.
By default it is community_users, but it may be an other table if you don't use
the dao file provided by the module.

ex:

```
jcommunity.installparam = "defaultuser;masteradmin"
```

Don't forget double quotes, else characters after ";" will be interpreted as a comment.

With jCommunity 1.1+, you can use jPref to allow to change some settings. If you
want to use it, you should also install the jpref module:

```
[modules]
jpref.access = 2
```

Setup with Jelix 1.7
---------------------

Launch the configurator for your application to enable the module

```bash
php yourapp/dev.php module:configure jcommunity
```

It will asks you some values for configuration parameters (see parameters in
the section above, about `jcommunity.installparam`), and will set for you the
`jcommunity.installparam` parameter in the configuration.

With jCommunity 1.1+, you can use jPref to allow to change some settings. If you
want to use it, you should also install the jpref module:

```bash
php yourapp/dev.php module:configure jpref
```

Finishing the setup
--------------------

To finish the setup, launch the installer

```
php yourapp/install/installer.php
```

It then creates a `community_users` table. If you have already a table of users, you can
add new fields of `community_users` in your table. You should then override all DAOs of
the jcommunity module to change fieldnames and the table. Or indicate a dao
of another module.

The auth coordplugin is automatically activated in your configuration. However,
verify in your ini file `yourapp/var/config/auth.coord.ini.php`, that you have these values: 

```
driver = Db
on_error_action = "jcommunity~login:out"
bad_ip_action = "jcommunity~login:out"
persistant_crypt_key=  "INSERT HERE A SENTENCE"

[Db]
; name of the dao to get user datas
dao = "jcommunity~user"
form = "jcommunity~account_admin"
```

Configuration
-------------

There are some settings that you can set in the configuration of your
application, or by jPref.

### settings in the main configuration (jCommunity 1.1+)

You should add a `jcommunity` section, and you can set these parameters:

- `loginResponse`: the alias of the response in the jcommunity controller
  to display the login form. By default: `html`.
- `passwordChangeEnabled`: activate or not the feature allowing to change our password.  By default: `on`.
- `accountDestroyEnabled`: activate or not to any user to delete his account.  By default: `on`.
- `registrationEnabled`: indicates if the registration feature is enabled
  (`on`) or not (`off`). By default: `on`
- `resetPasswordEnabled`: indicates if the reset password feature is enabled
  (`on`) or not (`off`). By default: `on`
- `disableJPref`: when `on`, indicates to not use jPref (see below) to store 
  "registrationEnabled" and "resetPasswordEnabled" preferences. By default: `off`.
- `verifyNickname`: says to verify or not the content of the nickname field
   when the user edit his profile.  By default: `on`. Set to `off` if you don't
   have such field.
- `useJAuthDbAdminRights`: when set to `on` (default value), jCommunity can
   rely on jAcl2 to check the right `auth.user.change.password` and `auth.user.modify` 
   to know if password change and account change is allowed.
- `publicProperties`: it indicates the list of users properties that can be shown
   to any other users, when his profile is shown. By default, only the login,
   the nickname and the creation date are shown.
- `noRedirectionOnAuthenticatedLoginPage`: set it to true if you don't want
   a redirection when the user goes on the login page whereas he is authenticated.
   

ex:

```
[jcommunity]
loginresponse = html  ; htmlauth for master_admin
registrationEnabled = off
resetPasswordEnabled = on
publicProperties=login,firstname,lastname
```


### settings with jPref (jCommunity 1.1+)

Using jPref allows to the admin user to change some settings (registration
and reset password) directly from the jpref_admin interface. If jPref is activated,
settings `registrationEnabled` and `resetPasswordEnabled` in the
configuration are ignored.

You can disable the use of jPref with the option `disableJPref`. See above.



Integration into your application
---------------------------------

You can integrate the "status" zone into your main template (directly into the template or
via your main response).

```
  $response->body->assignZone('STATUS', 'jcommunity~status');
```

It shows links to the login form, the register form if the user isn't authenticated, or to
the logout page and account page if he is authenticated.


You can change the start action in index/config.ini.php like this:

```
    startModule=jcommunity
    startAction="login:index"
```


if you use significant urls, link urls_account.xml, urls_auth.xml and
urls_registration.xml to the main urls.xml of the application

```
    <url pathinfo="/auth"     module="jcommunity" include="urls_auth.xml"/>
    <url pathinfo="/profile"  module="jcommunity" include="urls_account.xml"/>
    <url pathinfo="/registration"  module="jcommunity" include="urls_registration.xml"/>
```



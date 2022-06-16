Changes
=======

Version 2.2.1 (2021-09-16)
--------------------------

- Fix an error during the installation


Version 2.2.0 (2020-11-09)
--------------------------

- Implements jIAuthDriver2 interface available in Jelix 1.6.21+


Version 2.1.1 (2020-04-16)
---------------------------

- Fix password verification: close ldap connection in case of errors
- Fix error message when the loading of user attributes fails

Version 2.1.0 (2020-03-24)
---------------------------

Compatibility with Jelix 1.7.x


Version 2.0.10 (2020-03-24)
----------------------------

remove the dependency to jauth in module.xml, as it is a conflict when
jcommunity is installed.


Version 2.0.9 (2020-02-29)
--------------------------

Add more messages into logs when something wrong happens, to ease the debugging 
of bad connections / authentication / search.

Version 2.0.8 (2019-11-18)
--------------------------

- Improve support of ldaps and STARTTLS, by using ldap uri and ldap_start_tls()


Version 2.0.7 (2019-04-18)
--------------------------

- Fix SQL error during the installation


Version 2.0.6 (2019-01-28)
--------------------------

- Security issue: empty password may be accepted by LDAP servers, as they 
  implement the Unauthenticated Authentication Mecanism, allowing empty password.
  We don't want such feature, so empty password are now refused. 
- Fix user group search: escape parenthesis in values inserted into the 
  searchGroupFilter name.
- Fix user group search: add the user in default groups of the application,
  in addition to groups corresponding to ldap groups. New configuration 
  parameter searchGroupKeepUserInDefaultGroups to disable this feature.

Version 2.0.5 (2018-11-20)
--------------------------

- fix error during the installation

Version 2.0.4 (2018-07-27)
--------------------------

- fix some unit tests

Version 2.0.3 (2018-07-03)
--------------------------

- Fix: allow the admin user to change his password
- Fix: in some case, the user was recognized only at the first login

Version 2.0.2 (2018-06-08)
--------------------------

- Fix support of jCommunity

Version 2.0.1 (2018-01-29)
--------------------------

- Fix: attributes search were made anonymously, which is not allowed in some 
  LDAP server
- Fix support of attributes declaration having no mapping like `foo:`
- Improved the configuration manual
- Fix some unit tests


Version 2.0.0 (2017-04-05)
--------------------------

The login process has changed, to take care of various ldap structure and 
server configuration.

- support of multiple search filters for users
- support of multiple dn templates to connect users
- move ldap connection parameters (hostname, port, admin login/password)
  to profiles.ini.php
- Jelix admin login is configurable
- synchronize all ldap groups into jAcl2 rights, if configured

Version 1.1.1 (2017-02-08)
--------------------------

- Fix mispelling variable names


Version 1.1.0 (2015-06-03)
--------------------------

Initial public release.

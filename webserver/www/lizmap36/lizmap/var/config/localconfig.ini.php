;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

; put here configuration variables that are specific to this installation


; chmod for files created by Lizmap and Jelix
;chmodFile=0664
;chmodDir=0775



[modules]
;; uncomment it if you want to use ldap for authentication
;; see documentation to complete the ldap configuration
;ldapdao.enable=on


[coordplugin_auth]
;; uncomment it if you want to use ldap for authentication
;; see documentation to complete the ldap configuration
;driver=ldapdao


[mailer]
;; to send email via SMTP, uncomment this line, and fill the section smtp:mailer into profiles.ini.php
;mailerType=smtp


[coordplugins]
lizmap=lizmapConfig.ini.php

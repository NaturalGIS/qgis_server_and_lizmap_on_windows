#!/bin/sh

set -e
set -x

sed -i "/^user =/c\user = usertest"   /etc/php7/php-fpm.d/www.conf
sed -i "/^group =/c\group = grouptest" /etc/php7/php-fpm.d/www.conf

if [ -n "$TLS_CA_CRT_FILENAME" ]; then
    cp -a /customcerts/$TLS_CA_CRT_FILENAME /etc/ssl/certs/tests_CA.crt
    chown root:grouptest /etc/ssl/certs/tests_CA.crt
    chmod 0444 /etc/ssl/certs/tests_CA.crt
fi

if [ -n "$LDAP_TLS_CRT_FILENAME" ]; then
    cp -a /customcerts/$LDAP_TLS_KEY_FILENAME /etc/ssl/ldap/ldap.key
    cp -a /customcerts/$LDAP_TLS_CRT_FILENAME /etc/ssl/ldap/ldap.crt
    chown root:grouptest /etc/ssl/ldap/ldap.key
    chown root:grouptest /etc/ssl/ldap/ldap.crt
    chmod 0444 /etc/ssl/ldap/ldap.crt
    chmod 0440 /etc/ssl/ldap/ldap.key
fi

sh /bin/appctl.sh launch

echo "launch exec $@"
exec "$@"

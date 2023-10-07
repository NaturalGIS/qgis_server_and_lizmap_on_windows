#!/bin/bash

set -e
set -x

if [ -n "$TLS_CA_CRT_FILENAME" ]; then
    cp -a /customcerts/$TLS_CA_CRT_FILENAME /etc/ssl/certs/tests_CA.crt
    cp -a /customcerts/$TLS_CA_CRT_FILENAME /usr/local/share/ca-certificates/tests_CA.crt
    update-ca-certificates
    chown root:groupphp /etc/ssl/certs/tests_CA.crt
    chmod 0444 /etc/ssl/certs/tests_CA.crt
fi

if [ -n "$LDAP_TLS_CRT_FILENAME" ]; then
    cp -a /customcerts/$LDAP_TLS_KEY_FILENAME /etc/ssl/ldap/ldap.key
    cp -a /customcerts/$LDAP_TLS_CRT_FILENAME /etc/ssl/ldap/ldap.crt
    chown root:groupphp /etc/ssl/ldap/ldap.key
    chown root:groupphp /etc/ssl/ldap/ldap.crt
    chmod 0444 /etc/ssl/ldap/ldap.crt
    chmod 0440 /etc/ssl/ldap/ldap.key
fi

#/bin/appctl.sh launch

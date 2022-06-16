FROM alpine:3.9

ARG composer_version=2.1.6
ARG user_id
ARG group_id

ENV FPM_USER_ID=${user_id}
ENV FPM_GROUP_ID=${group_id}
ENV JELIX_VERSION=1.6.34

RUN apk update && apk upgrade
RUN apk --no-cache add git php7 php7-fpm \
    php7-tokenizer \
    php7-opcache \
    php7-session \
    php7-iconv \
    php7-intl \
    php7-mbstring \
    php7-openssl \
    php7-fileinfo \
    php7-curl \
    php7-json \
    php7-sqlite3 \
    php7-gd \
    php7-dom \
    php7-xml \
    php7-xmlrpc \
    php7-xmlreader \
    php7-xmlwriter \
    php7-simplexml \
    php7-zip \
    php7-phar \
    php7-gettext \
    php7-ctype \
    php7-ldap \
    openssl \
    wget

RUN set -eux; \
    sed -i "/^listen =/c\listen = 9000" /etc/php7/php-fpm.d/www.conf; \
    sed -i "/^display_errors =/c\display_errors = On" /etc/php7/php.ini; \
    addgroup -g $FPM_GROUP_ID grouptest; \
    adduser -D -u $FPM_USER_ID -G grouptest usertest; \
    mkdir -p /etc/openldap/ /etc/ssl/ldap/; \
    chmod 755 /etc/openldap/ /etc/ssl/ldap/;


## Install Composer
RUN wget -O /bin/composer https://getcomposer.org/download/$composer_version/composer.phar \
    && chmod +x /bin/composer

COPY ldap.conf /etc/openldap/ldap.conf
COPY www.conf /etc/php7/php-fpm.d/www.conf
COPY entrypoint.sh /bin/
COPY appctl.sh /bin/
COPY profile.start /etc/profile.d/start
RUN chmod 755 /bin/entrypoint.sh /bin/appctl.sh

ENV PHP_INI_DIR /etc/php7

WORKDIR /jelixapp/
ENTRYPOINT ["/bin/entrypoint.sh"]
CMD ["/usr/sbin/php-fpm7", "-F", "-O"]

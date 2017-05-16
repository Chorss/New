FROM php:7-apache

ENV XDEBUG_VERSION 2.5.4

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
            libmcrypt-dev \
            libpq-dev \
            libcurl3-dev \
            git-core \
            nano
RUN docker-php-ext-install bcmath curl exif fileinfo gd hash iconv json mbstring mcrypt mysqli pgsql opcache pdo pdo_mysql pdo_pgsql phar sockets session sysvmsg zip
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN apt-get update \
    && apt-get install -y libmemcached-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN a2enmod rewrite
RUN apt-get clean  -y\
    && rm -rf /var/lib/apt/lists/* \
    && apt-get -y autoremove \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /var/cache/debconf/*-old \
    && rm -rf /usr/share/doc/* \
    && cp -R /usr/share/locale/en\@* /tmp/ && rm -rf /usr/share/locale/* && mv /tmp/en\@* /usr/share/locale/

WORKDIR "/var/www"
EXPOSE 80
CMD ["apache2-foreground"]
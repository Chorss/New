FROM php:7.0-apache
RUN apt-get update && apt-get upgrade && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpq-dev \
	libcurl3-dev 
RUN docker-php-ext-install bcmath curl exif fileinfo gd hash iconv json mbstring mcrypt 
RUN docker-php-ext-install mysqli pgsql opcache pdo pdo_mysql pdo_pgsql phar session sockets sysvmsg zip 
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN apt-get update && apt-get install -y libmemcached-dev 
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
RUN a2enmod rewrite
CMD ["apache2-foreground"]

FROM php:7.1-fpm

WORKDIR /var/www/html/public

# Install PHP extensions and PECL modules.
RUN buildDeps=" \
        libbz2-dev \
        default-libmysqlclient-dev \
        libsasl2-dev \
    " \
    runtimeDeps=" \
        curl \
        git \
        libfreetype6-dev \
        libicu-dev \
        libjpeg-dev \
        libldap2-dev \
        libmcrypt-dev \
        libmemcachedutil2 \
        libpng-dev \
        libpq-dev \
        libxml2-dev \
    " \
    && apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y $buildDeps $runtimeDeps \
    && docker-php-ext-install bcmath bz2   intl mbstring mcrypt mysqli opcache  pdo_pgsql pgsql   \
    && apt-get purge -y --auto-remove $buildDeps \
    && rm -r /var/lib/apt/lists/*


# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s $(composer config --global home) /root/composer

#ADD  . .

#RUN composer install

ENV PATH $PATH:/root/composer/vendor/bin


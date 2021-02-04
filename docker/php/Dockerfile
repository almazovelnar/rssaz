FROM php:7.4-fpm

LABEL maintainer="vagif@rufullazada.me"

# Cleaning and updating packages
RUN apt-get clean && apt-get update

# Installing some packages
RUN apt-get install -y --no-install-recommends \
 libfreetype6-dev libjpeg-dev libwebp-dev libjpeg62-turbo-dev libpng-dev libicu-dev libzip-dev procps zip unzip git

# Configuring php extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp && \
    docker-php-ext-configure intl

# Installing php extensions
RUN docker-php-ext-install pdo pdo_mysql gd bcmath intl

# Setting up composer
RUN curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/ \
        && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

# Creating user for container.
RUN groupadd -g 1000 appuser && useradd -r -u 1000 -g appuser appuser
USER appuser

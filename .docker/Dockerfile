FROM php:8.3-apache

ARG HOST_UID=1000
ARG HOST_GID=1000

# System libs for PHP extensions + graphviz (dot) + mariadb client (for iTop backups)
RUN apt-get update && apt-get install -y --no-install-recommends \
        graphviz \
        mariadb-client \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev \
        libicu-dev libxml2-dev libzip-dev libonig-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd intl mysqli soap zip mbstring \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

# ctype, dom, iconv, json, xml are compiled into the official image already.

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    && { \
        echo 'memory_limit=512M'; \
        echo 'upload_max_filesize=32M'; \
        echo 'post_max_size=32M'; \
        echo 'max_execution_time=300'; \
        echo 'max_input_vars=5000'; \
        echo 'date.timezone=Europe/Paris'; \
        echo 'display_errors=Off'; \
        echo 'log_errors=On'; \
        echo 'apc.enable_cli=1'; \
    } > "$PHP_INI_DIR/conf.d/zz-itop.ini"

# Allow .htaccess files shipped with iTop
RUN a2enmod rewrite headers \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# Run Apache as the host user so the bind-mounted checkout is writable
# (conf/, data/, env-production/, log/) without chmod-ing the repo.
RUN usermod -o -u "${HOST_UID}" www-data \
    && (getent group "${HOST_GID}" >/dev/null || groupmod -o -g "${HOST_GID}" www-data) \
    && usermod -g "${HOST_GID}" www-data \
    && chown -R www-data /var/www

WORKDIR /var/www/html

FROM php:8.1.0-fpm-alpine

# Install packages
RUN apk add --update --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash linux-headers

# Xdebug
ARG INSTALL_XDEBUG=false

RUN if [ ${INSTALL_XDEBUG} = true ]; then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

# Postgres
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_pgsql

# Mysql
#RUN docker-php-ext-install pdo_mysql

# Configure non-root user.
ARG UID
ARG GID
ARG USER
ARG GROUP

RUN addgroup -g ${GID} -S ${GROUP} \
    && adduser -u ${UID} -S -D -H ${USER} ${GROUP}

# Workdir
WORKDIR /var/www

# Config file
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Remove docker config file
RUN rm /usr/local/etc/php-fpm.d/zz-docker.conf

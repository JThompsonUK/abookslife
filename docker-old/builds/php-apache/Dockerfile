FROM openhealthdigital/php-apache:8.1-test

RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

COPY --from=composer /usr/bin/composer /usr/bin/composer

FROM phpdockerio/php72-fpm:latest

WORKDIR "/application"

COPY . /application
COPY /docker/ci/php-fpm/php-ini-overrides.ini /etc/php/7.2/fpm/conf.d/99-overrides.ini


# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.2-mysql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Using 'dummy' values to avoid errors in cases, when required env variables aren't provided

RUN APP_ENV=prod MAILER_URL=dummy MAILER_SENDER=dummy MAILER_RECIPIENT=dummy composer install --no-dev --optimize-autoloader \
    # Waming-up cache
    && bin/console cache:warmup \
    # Providing write permissions
    && chmod -R 777 var/
# https://github.com/denderello/symfony-docker-example/issues/2

# TODO: execute migrations(if any) automatically

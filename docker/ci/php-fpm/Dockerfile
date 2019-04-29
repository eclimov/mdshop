FROM phpdockerio/php72-fpm:latest

WORKDIR "/application"

COPY . /application
COPY /docker/ci/php-fpm/php-ini-overrides.ini /etc/php/7.2/fpm/conf.d/99-overrides.ini

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.2-mysql \
    && apt-get -y --no-install-recommends install php7.2-gd \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Using 'dummy' values to avoid errors in cases, when required env variables aren't provided
ENV APP_ENV=prod
ENV MAILER_URL=dummy
ENV MAILER_SENDER=dummy
ENV MAILER_RECIPIENT=dummy

# Waming-up cache + Providing write permissions
# https://github.com/denderello/symfony-docker-example/issues/2
RUN composer install --no-dev --optimize-autoloader \
    && bin/console cache:warmup \
    && chmod -R 777 var/ \
    && chmod -R 777 public/uploads/

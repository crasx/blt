ARG PHP_VERSION
FROM php:${PHP_VERSION}-apache as drupal_build

ARG NEW_RELIC_AGENT_VERSION=10.13.0.2
ARG NEW_RELIC_APP_NAME
ARG NEW_RELIC_LICENSE_KEY
ARG MEMORY_LIMIT="512M"

# Step 1: Build stage
COPY vendor/crasx/blt /opt/blt


# install the PHP extensions we need
RUN --mount=type=cache,target=/var/lib/apt/lists/ \
    chmod +x /opt/blt/scripts/docker/* && \
    /opt/blt/scripts/docker/php.sh && \
    /opt/blt/scripts/docker/newrelic.sh && \
    chmod +x /usr/local/bin/docker-php-entrypoint && \
     ( [ -f deploy/docker/container-build.sh ] && deploy/docker/container-build.sh || true ) ;
#   cp -f /opt/blt/docker/docker-php-entrypoint /usr/local/bin/docker-php-entrypoint && \

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
    		echo 'opcache.memory_consumption=128'; \
    		echo 'opcache.interned_strings_buffer=8'; \
    		echo 'opcache.max_accelerated_files=4000'; \
    		echo 'opcache.revalidate_freq=60'; \
    	} > /usr/local/etc/php/conf.d/opcache-recommended.ini; \
        { \
            echo 'memory_limit='$MEMORY_LIMIT; \
  	    } > /usr/local/etc/php/conf.d/drupal.ini

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/


# Set working directory
WORKDIR /opt/drupal


## Run composer commands

COPY --chown=www-data:www-data  . .

RUN rm -rf /var/www/html && \
	ln -sf /opt/drupal/web /var/www/html && \
    chown -R www-data:www-data /var/www/html && \
     cp /opt/blt/scripts/liveness.php /var/www/html/ && \
    chown -R www-data:www-data /opt/drupal ;

USER www-data

ENV PATH=${PATH}:/opt/drupal/vendor/bin

#     --mount=type=cache,target=/root/.composer/cache \
RUN  \
    ( [ -f deploy/docker/theme-build.sh ] && deploy/docker/theme-build.sh || true ) && \
   rm -rf vendor && \
   COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --prefer-dist && \
	ln -sf /mnt/sites/default/files /opt/drupal/web/sites/default/files \
     ;


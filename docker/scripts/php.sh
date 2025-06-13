#!/bin/bash

PACKAGES="default-mysql-client \
git \
unzip \
libfreetype6-dev \
libjpeg-dev \
libmemcached-dev \
libpng-dev \
libpq-dev \
libwebp-dev \
libmagickwand-dev \
libzip-dev \
nano \
vim"



set -eux;

if command -v a2enmod; then
  a2enmod rewrite;
fi

savedAptMark="$(apt-mark showmanual)";
echo "Install packages: $PACKAGES";
#echo $savedAptMark;

apt-get update;

apt-get install -y --no-install-recommends $PACKAGES ;

pecl install \
    memcached \
    imagick ;

docker-php-ext-configure gd \
  --with-freetype \
  --with-jpeg=/usr \
  --with-webp	;

docker-php-ext-install -j "$(nproc)" \
  gd \
  opcache \
  pdo_mysql \
  pdo_pgsql \
  zip \
  bcmath;

docker-php-ext-enable \
  memcached \
  imagick \
;

apt-mark auto '.*' > /dev/null;
[ -z "$savedAptMark" ] || apt-mark manual $savedAptMark;
apt-mark manual $PACKAGES;

find /usr/local -type f -executable -exec ldd '{}' ';' \
  | awk '/=>/ { so = $(NF-1); if (index(so, "/usr/local/") == 1) { next }; gsub("^/(usr/)?", "", so); printf "*%s\n", so }' \
  | sort -u \
  | xargs -r dpkg-query --search \
  | cut -d: -f1 \
  | sort -u \
  | xargs -r apt-mark manual \
;
apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false;
rm -rf /var/lib/apt/lists/*;
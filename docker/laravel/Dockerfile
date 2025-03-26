FROM php:8.2-fpm-bookworm

# https://github.com/mlocati/docker-php-extension-installer/blob/master/README.md
RUN set -eux; \
    curl -sSLf \
            -o /usr/local/bin/install-php-extensions \
            https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions; \
    chmod +x /usr/local/bin/install-php-extensions; \
    install-php-extensions  \
      pgsql \
      pdo_pgsql \
      zip \
      intl \
      @composer \
    ;

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


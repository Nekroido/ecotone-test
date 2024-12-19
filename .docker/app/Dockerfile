FROM bitnami/php-fpm:latest AS base

# Install autoconf and other necessary build tools
RUN install_packages autoconf build-essential librabbitmq-dev

# Bump pecl version
RUN pecl channel-update pecl.php.net

# Install dependencies
RUN pecl install amqp

# Enable the extension
RUN echo "extension=amqp.so" > /opt/bitnami/php/etc/conf.d/amqp.ini

# Install wait-for-it
RUN curl -L https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh -o /usr/local/bin/wait-for-it \
    && chmod +x /usr/local/bin/wait-for-it

RUN mkdir -p /entrypoint.d
COPY .docker/app/scripts/ /entrypoint.d/
RUN chmod +x /entrypoint.d/*.sh -R

ENTRYPOINT ["/entrypoint.d/bootstrap.sh"]

FROM base AS dev

# Install development dependencies
RUN pecl install xdebug

# Configure Xdebug
RUN echo "zend_extension=$(find /opt/bitnami/php/lib/php/extensions/ -name xdebug.so)" > /opt/bitnami/php/etc/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /opt/bitnami/php/etc/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /opt/bitnami/php/etc/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /opt/bitnami/php/etc/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /opt/bitnami/php/etc/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /opt/bitnami/php/etc/conf.d/xdebug.ini \
    && echo "xdebug.log=/tmp/xdebug.log" >> /opt/bitnami/php/etc/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /opt/bitnami/php/etc/conf.d/xdebug.ini

FROM base AS prod

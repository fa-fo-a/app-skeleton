FROM ubuntu:20.04

RUN apt update &&\
    apt install -y \
    software-properties-common \
    libicu-dev \
    libzip-dev \
    nginx \
    unzip \
    tzdata \
    curl

ENV TZ="Europe/Athens"
RUN apt install -y git

RUN add-apt-repository -y ppa:ondrej/php &&\
    apt update -y &&\
    apt install -y php8.3 \
        php8.3-fpm \
        php8.3-xdebug \
        php8.3-mysql \
        php8.3-intl \
        php8.3-zip \
        php8.3-dom \
        php8.3-pcov \
        php8.3-mbstring \
    &&\
    apt purge -y apache2

RUN echo "zend_extension=$(find /usr/lib/php -name xdebug.so)" >> /etc/php/8.3/mods-available/xdebug.ini &&\
    echo "xdebug.mode=debug,coverage" >> /etc/php/8.3/mods-available/xdebug.ini &&\
    echo "xdebug.start_with_request=yes" >> /etc/php/8.3/mods-available/xdebug.ini &&\
    echo "xdebug.client_host=host.docker.internal" >> /etc/php/8.3/mods-available/xdebug.ini &&\
    echo "xdebug.client_port=9001" >> /etc/php/8.3/mods-available/xdebug.ini

RUN echo "pcov.enabled=0" >> /etc/php/8.3/cli/php.ini
RUN echo "pcov.directory=/var/www/html" >> /etc/php/8.3/cli/php.ini

RUN echo "short_open_tag=off" >> /etc/php/8.3/cli/php.ini

RUN mkdir /run/php

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

###> recipes ###
###

RUN apt install -y mysql-server

RUN apt install -y supervisor

RUN mkdir -p /tmp/log/supervisor
COPY docker/supervisor/app.conf /etc/supervisor/conf.d/app.conf
COPY docker/dev/app.conf /etc/nginx/sites-enabled/default
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

RUN service mysql start &&\
    mysql -e "CREATE DATABASE \`app\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" &&\
    mysql -e "CREATE DATABASE \`app_test\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" &&\
    mysql -e "create user 'app'@'%' identified by 'pass';" &&\
    mysql -e "grant all on \`app\`.* to 'app'@'%';" &&\
    mysql -e "grant all on \`app_test\`.* to 'app'@'%';"

RUN groupadd -g 1000 -o app
RUN useradd -m -u 1000 -g 1000 -o -s /bin/bash app

RUN echo 'user = app' >> /etc/php/8.3/fpm/pool.d/www.conf &&\
    echo 'group = app' >> /etc/php/8.3/fpm/pool.d/www.conf &&\
    echo 'listen.owner = app' >> /etc/php/8.3/fpm/pool.d/www.conf &&\
    echo 'listen.group = app' >> /etc/php/8.3/fpm/pool.d/www.conf

ENV COMPOSER_ALLOW_SUPERUSER=1

CMD /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf

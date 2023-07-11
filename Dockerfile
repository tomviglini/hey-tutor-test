FROM phusion/baseimage:focal-1.2.0

RUN locale-gen en_US.UTF-8
ENV LANG       en_US.UTF-8
ENV LC_ALL     en_US.UTF-8

ENV COMPOSER_ALLOW_SUPERUSER=1

ENV HOME /root

RUN /etc/my_init.d/00_regen_ssh_host_keys.sh

CMD ["/sbin/my_init"]

RUN apt-get update

RUN DEBIAN_FRONTEND="noninteractive" apt-get install --fix-missing -y \
vim \
curl \
wget \
build-essential \
software-properties-common \
ca-certificates \
git-core \
ssh \
zip  \
unzip  \
nano

RUN add-apt-repository -y ppa:ondrej/php
RUN add-apt-repository -y ppa:nginx/stable
RUN apt-get update


RUN DEBIAN_FRONTEND="noninteractive" apt-get install --fix-missing -y \
--allow-downgrades \
--allow-remove-essential \
--allow-change-held-packages \
php8.2-cli \
php8.2-fpm \
php8.2-xml \
php8.2-curl \
php8.2-mysql

RUN DEBIAN_FRONTEND="noninteractive" apt-get install --fix-missing -y \
--allow-downgrades \
--allow-remove-essential \
--allow-change-held-packages \
nginx


RUN DEBIAN_FRONTEND="noninteractive" apt-get install --fix-missing -y \
--allow-downgrades \
--allow-remove-essential \
--allow-change-held-packages \
mysql-client



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer --version=2.5.8


RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php/8.2/fpm/php-fpm.conf


ADD build/php-custom.ini    /etc/php/8.2/fpm/conf.d
ADD build/www.conf          /etc/php/8.2/fpm/pool.d/www.conf

ADD build/opcache.ini       /etc/php/8.2/mods-available/opcache.ini
ADD build/xdebug.ini        /etc/php/8.2/mods-available/xdebug.ini

ADD build/default           /etc/nginx/sites-available/default
ADD build/nginx.conf        /etc/nginx/nginx.conf


WORKDIR /code/api

# ADD .env.docker ./.env

# ADD composer.json ./
# ADD composer.lock ./

ADD . ./

# RUN composer install --no-ansi --no-interaction --no-progress --optimize-autoloader
RUN composer install --no-ansi --no-interaction --no-progress --optimize-autoloader --no-dev


RUN service php8.2-fpm start


RUN mkdir                   /etc/service/nginx
ADD build/nginx.sh          /etc/service/nginx/run
RUN chmod +x                /etc/service/nginx/run

RUN mkdir                   /etc/service/phpfpm
ADD build/phpfpm.sh         /etc/service/phpfpm/run
RUN chmod +x                /etc/service/phpfpm/run

VOLUME /code

EXPOSE 80

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

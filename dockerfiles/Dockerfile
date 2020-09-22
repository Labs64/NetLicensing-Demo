FROM centos:8

LABEL maintainer="Labs64 NetLiecnsing info@netlicensing.io"

# Install some must-haves
RUN yum -y install vim wget sendmail
RUN yum -y install libtool make automake autoconf
RUN yum -y install git
RUN git --version

# Install PHP on CentOS ( https://linuxize.com/post/how-to-install-php-on-centos-8/ )
RUN dnf install -y dnf-utils http://rpms.remirepo.net/enterprise/remi-release-8.rpm
RUN dnf module list php
RUN dnf module reset php
RUN dnf module -y enable php:remi-7.4

RUN dnf -y install php \
					         php-bcmath \
					         php-cli \
					         php-common \
					         php-curl \
					         php-fpm \
					         php-gd \
					         php-ldap \
					         php-imap \
					         php-intl \
					         php-mbstring \
					         php-mcrypt \
					         php-mysqlnd \
					         php-opcache \
					         php-pdo \
					         php-pear \
					         php-pecl-apcu \
					         php-pecl-imagick \
					         php-pgsql \
					         php-process \
					         php-pspell \
					         php-recode \
					         php-soap \
					         php-tidy \
					         php-xml

RUN php -v

# Prepare PHP environment
COPY config/php/php-fpm.conf /etc/php-fpm.conf
COPY config/php/www.conf /etc/php-fpm.d/www.conf
COPY config/php/php.ini /usr/local/etc/php/php.ini
COPY config/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer
RUN composer --version

# Install Node.js
RUN curl -sL https://rpm.nodesource.com/setup_12.x | bash -
RUN yum -y install nodejs
RUN yum list installed nodejs
RUN node -v

# Final update and clean up
RUN yum -y update
RUN yum clean all

# Define work directory
WORKDIR /var/www/netlicensing-demo

# Expose ports
EXPOSE 9000

CMD ["php-fpm", "-F", "-O"]

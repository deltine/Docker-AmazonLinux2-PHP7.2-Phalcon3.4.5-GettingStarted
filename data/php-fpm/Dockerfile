FROM amazonlinux:2.0.20180622.1

USER root

# システムのアップデート
RUN yum update -y

# システムの言語を日本語に対応させる
RUN yum install -y glibc-langpack-ja \
    unlink /etc/localtime \
    ln -s /usr/share/zoneinfo/Japan /etc/localtime \
    echo "LANG=ja_JP.UTF-8" | tee /etc/sysconfig/i18n

# タイムゾーンを日本に変更
RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    echo -e 'ZONE="Asia/Tokyo"\nUTC=true' | tee /etc/sysconfig/clock

# PHPをインストール
RUN amazon-linux-extras install -y php7.2

# peclのインストール
RUN yum install php-pear php-devel gcc make -y
RUN pecl update-channels
RUN pecl install psr

# xdebugをインストール
RUN pecl install xdebug

# wgetをインストール
RUN yum -y install wget
RUN yum -y install \
    mc nano git wget curl zip unzip htop re2c \
    php-cli \
    php-fpm \
    php-common \
    php-intl \
    php-mbstring \
    php-opcache \
    php-pdo \
    php-mysqlnd \
    php-xml \
    php-pecl-zip \
    && yum autoremove \
    && yum clean all 

# phalconリポジトリをインストール
RUN yum install -y https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
    yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm

# phalconのインストール
RUN yum install -y php72-php-phalcon3

# composerのインストール
WORKDIR /usr/bin
RUN curl -s http://getcomposer.org/installer | php
COPY conf/composer.json /usr/bin

# 出来なかった…
# RUN php composer.phar install
# RUN ln -s /usr/bin/vendor/phalcon/devtools/phalcon /usr/bin/phalcon
# RUN chmod ugo+x /usr/bin/phalcon

# php-fpmをフォアグラウンドで実行
ENTRYPOINT /usr/sbin/php-fpm -F
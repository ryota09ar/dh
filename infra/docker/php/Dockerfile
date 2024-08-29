# ベースイメージを指定
FROM php:8.2-fpm-buster
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

# 環境変数の設定
ENV TZ='Asia/Tokyo' \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer

# Composer のインストール
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# 必要なパッケージのインストール
RUN apt-get update && \
    apt-get -y install \
    git \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    locales \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    locale-gen en_US.UTF-8 && \
    localedef -f UTF-8 -i en_US en_US.UTF-8 && \
    mkdir /var/run/php-fpm && \
    docker-php-ext-install intl pdo_mysql zip bcmath calendar && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    composer config -g process-timeout 3600 && \
    composer config -g repos.packagist composer https://packagist.org

# 設定ファイルのコピー
COPY ./infra/docker/php/php-fpm.d/zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./infra/docker/php/php.ini /usr/local/etc/php/php.ini

# 作業ディレクトリの設定
WORKDIR /work/laravel
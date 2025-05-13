FROM php:8.3-fpm

# 1. 系統工具 + 必要 dev 套件
RUN apt-get update -y && apt-get install -y \
        git curl unzip \
        libzip-dev zlib1g-dev \          
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y python3 python3-pip

# 2. Composer（用官方 multi-stage 抓最乾淨）
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. yt-dlp 到 PATH
COPY yt-dlp /usr/local/bin/
RUN chmod +x /usr/local/bin/yt-dlp

RUN apt-get update && apt-get install -y ffmpeg


RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

# 4. 專案程式碼
WORKDIR /var/www
COPY . .

# 5. 安裝 PHP 依賴
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# 6. （可選）若你要在映像裡直接 build 前端靜態檔，解除註解👇
# RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
#     && apt-get install -y nodejs \
#     && npm ci && npm run build && npm cache clean --force

RUN php artisan storage:link
RUN php artisan key:generate



# php-fpm 監聽 unix socket（Nginx 會連這裡）
RUN { \
      echo '[global]'; \
      echo 'daemonize = no'; \
      echo '[www]'; \
      echo 'listen = 9000'; \
      echo 'listen.owner = www-data'; \
    } > /usr/local/etc/php-fpm.d/zz-docker.conf

EXPOSE 9000
CMD ["php-fpm"]
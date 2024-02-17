FROM php:8.1
EXPOSE 8000

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
COPY ../php /var/www/html
WORKDIR /var/www/html
RUN composer install
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port=8000"]
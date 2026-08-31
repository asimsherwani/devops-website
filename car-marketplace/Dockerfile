FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY src/ /var/www/html/

# Create directory for seller-uploaded vehicle pictures
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html

# Allow multiple image uploads
RUN printf "upload_max_filesize=5M\npost_max_size=45M\nmax_file_uploads=10\n" \
    > /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 80
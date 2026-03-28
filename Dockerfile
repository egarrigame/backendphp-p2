FROM php:8.2-apache 
RUN docker-php-ext-install pdo pdo_mysql mysqli 
RUN a2enmod rewrite 
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf 
RUN chown -R www-data:www-data /var/www/html 
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini 
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini 
WORKDIR /var/www/html 

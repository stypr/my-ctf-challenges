FROM php:7.4.33-apache

# Set up dependencies
RUN apt-get update
RUN apt-get install -y nano net-tools sudo

# Set up apache
WORKDIR /srv
RUN a2enmod rewrite
COPY ./file.tar.gz /var/www/html
COPY ./apache2.conf /tmp

# Install CMS
WORKDIR /var/www/html
RUN tar xvf file.tar.gz
RUN mv file.tar.gz /root/file.tar.gz
RUN chown -R root:www-data /var/www/html/
RUN chmod -R 750 /var/www/html/
RUN chmod -R 770 /var/www/html/s3cr3tf1nalp4ge/files /var/www/html/s3cr3tf1nalp4ge/image /var/www/html/s3cr3tf1nalp4ge/js /var/www/html/s3cr3tf1nalp4ge/css

# Security :)
RUN chmod 1733 /tmp /var/tmp /dev/shm && chmod 731 /proc
RUN mv /tmp/apache2.conf /etc/apache2/apache2.conf

EXPOSE 80

FROM php:7.4.12-apache
RUN apt-get update
RUN apt-get install -y nano
COPY ./www /var/www/html
RUN chown -R root:root /var/www/html/
RUN chmod 644 /var/www/html/*
RUN mkdir /var/www/html/uploads && chmod 1777 /var/www/html/uploads
RUN mkdir /var/www/html/uploads/.keep && chown root:root /var/www/html/uploads/.keep
RUN chmod 1733 /tmp /var/tmp /dev/shm /proc
RUN echo "www-data hard nproc 300" > /etc/security/limits.conf

EXPOSE 80

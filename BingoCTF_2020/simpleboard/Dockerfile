FROM wyveo/nginx-php-fpm:php74
RUN chmod 1733 /tmp /var/tmp /dev/shm /proc
RUN echo "www-data hard nproc 300" > /etc/security/limits.conf
RUN echo "nginx hard nproc 300" > /etc/security/limits.conf

EXPOSE 80

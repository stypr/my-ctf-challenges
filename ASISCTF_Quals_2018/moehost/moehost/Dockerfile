FROM docker:dind
MAINTAINER stypr (me@harold.kim)

ENV TERM xterm
ADD ./www/ /srv/
WORKDIR /srv

RUN apk update && apk add nginx nano wget curl
RUN apk add php7 php7-curl php7-mysqli php7-fpm python
RUN adduser -D -h /srv/ -s /bin/false moe && \
adduser -D -g 'www' www && \
chown -R moe:www /srv/ && \
chmod -R 550 /srv/ && \
chmod g+s /srv/

RUN wget --no-check-certificate http://10.1.0.1:1337/moehost/nginx.conf && \
mv nginx.conf /etc/nginx/nginx.conf && \
echo "nginx set"
RUN wget --no-check-certificate http://10.1.0.1:1337/moehost/php.conf && \
sed -i 's/username/moe/g' php.conf && \
mv php.conf /etc/php7/php-fpm.d/www.conf && \
echo "php-fpm set"
RUN wget --no-check-certificate http://10.1.0.1:1337/moehost/secure.py && \
mv secure.py /root && \
echo "* * * * * python /root/secure.py 2>/dev/null >/dev/null" >> /etc/crontabs/root
RUN echo 'rm -rf /.dockerenv;php-fpm7;crond;cat /dev/null>/etc/rc.local;/usr/sbin/nginx -g "daemon off;"' > /etc/rc.local && \
chmod +x /etc/rc.local
RUN rm -rf /.dockerenv

USER root
EXPOSE 80
ENTRYPOINT /etc/rc.local


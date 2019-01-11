FROM debian:stretch

RUN apt-get update
RUN apt-get install git graphviz apache2 php7.0 php7.0-mysql php7.0-gd php7.0-ldap php7.0-mcrypt php7.0-opcache php7.0-soap php7.0-xml php7.0-zip mariadb-client -y

RUN rm -rf /var/www/html; git clone --depth=1 https://github.com/Combodo/iTop /var/www/html
RUN cd /var/www/html; mkdir -p conf data log env-production env-production-build
RUN cd /var/www/html; chown -R www-data:www-data conf data log env-production env-production-build

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_CONFDIR /etc/apache2
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_RUN_DIR /var/run/apache2

CMD ["/usr/sbin/apache2", "-D", "FOREGROUND"]

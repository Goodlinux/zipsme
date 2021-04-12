FROM alpine:latest
MAINTAINER Ludovic MAILLET <Ludo.goodlinux@gmail.com>

RUN apk -U add php8 php8-fpm php8-mysqli nginx git tzdata

EXPOSE 80

ENV DB_USER=zipsme \
    DB_PASSWORD=DbPassword \
    DB_NAME=zipsme \
    DB_SERVER=192.168.10.159:3306 \
    SITE_NAME='URL Shortener' \
    SITE_URL=http://go  \
    ZIPSME_ADMIN_USER=ZipAdminUser \
    ZIPSME_ADMIN_PASSWORD=ZipAdminPassword \
    TZ=Europe/Paris

#Construction of redirection and php use for nginx
RUN echo "server { " > /etc/nginx/conf.d/default.conf  \
    &&  echo "        listen 80 default_server; " >> /etc/nginx/conf.d/default.conf	\
    &&  echo "        listen [::]:80 default_server; " >> /etc/nginx/conf.d/default.conf	\
    &&  echo "        index index.php index.html index.htm; " >> /etc/nginx/conf.d/default.conf	  \
    &&  echo "        root /var/www/zipsme; " >> /etc/nginx/conf.d/default.conf		\
    &&  echo "		if (!-e \$request_filename){ " >> /etc/nginx/conf.d/default.conf		\
    &&  echo "			rewrite ^/([A-Za-z0-9-]+)/?$ /redirect.php?url_name=\$1 break; } " >> /etc/nginx/conf.d/default.conf	\
    &&  echo "    location ~ \.php$ { " >> /etc/nginx/conf.d/default.conf	\
    &&  echo "        try_files \$uri =404; " >> /etc/nginx/conf.d/default.conf	\
    &&  echo "        fastcgi_pass localhost:9000; " >> /etc/nginx/conf.d/default.conf	\
    &&  echo "        fastcgi_index index.php; " >> /etc/nginx/conf.d/default.conf	\
    &&  echo "        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; " >> /etc/nginx/conf.d/default.conf		\
    &&  echo "        include fastcgi_params;    } " >> /etc/nginx/conf.d/default.conf	\
    &&  echo "}  " >> /etc/nginx/conf.d/default.conf

#Import project from GitHub
RUN mkdir /run/nginx && git clone https://github.com/Goodlinux/zipsme.git /var/www/zipsme/ && rm /var/www/zipsme/Dockerfile

#Construction of entrypoint
RUN echo "#! /bin/sh" > /usr/local/bin/entrypoint.sh \
	&& echo "echo lancement de nginx" >> /usr/local/bin/entrypoint.sh  \
	&& echo "nginx" >> /usr/local/bin/entrypoint.sh  \
	&& echo "echo lancement de php" >> /usr/local/bin/entrypoint.sh  \
    	&& echo "php-fpm8" >> /usr/local/bin/entrypoint.sh  \
	&& echo "echo Timezone $TZ" >> /usr/local/bin/entrypoint.sh  \
	&& echo "cp /usr/share/zoneinfo/\$TZ /etc/localtime && echo \$TZ >  /etc/timezone" >> /usr/local/bin/entrypoint.sh \
	&& echo "cd /var/www/zipsme" >> /usr/local/bin/entrypoint.sh  \
	&& echo "echo mise a jour de la config php"  >>  /usr/local/bin/entrypoint.sh  \
	&& echo sed -i '"'"s|database_user|\$DB_USER|"'"' /var/www/zipsme/config.php >> /usr/local/bin/entrypoint.sh  \
	&& echo sed -i '"'"s|database_password|\$DB_PASSWORD|"'"' /var/www/zipsme/config.php  >> /usr/local/bin/entrypoint.sh  \
 	&& echo sed -i '"'"s|database_name|\$DB_NAME|"'"' /var/www/zipsme/config.php    >> /usr/local/bin/entrypoint.sh  \
 	&& echo sed -i '"'"s|localhost|\$DB_SERVER|"'"' /var/www/zipsme/config.php      >> /usr/local/bin/entrypoint.sh  \
 	&& echo sed -i '"'"s|Your Site|\$SITE_NAME|"'"' /var/www/zipsme/config.php      >> /usr/local/bin/entrypoint.sh  \
 	&& echo sed -i '"'"s|http://www.yoursite.com/zipsme|\$SITE_URL|"'"'  /var/www/zipsme/config.php    >> /usr/local/bin/entrypoint.sh  \
 	&& echo sed -i '"'"s|username|\$ZIPSME_ADMIN_USER|"'"' /var/www/zipsme/config.php       >> /usr/local/bin/entrypoint.sh  \
 	&& echo sed -i '"'"s|password|\$ZIPSME_ADMIN_PASSWORD|"'"' /var/www/zipsme/config.php   >> /usr/local/bin/entrypoint.sh  \
 	&& echo sed -i '"' "s|'America/New_York'|getenv('TZ')|"'"'   /var/www/zipsme/config.php   >> /usr/local/bin/entrypoint.sh  \
	&& echo "exec /bin/sh" >> /usr/local/bin/entrypoint.sh  \
	&& chmod a+x /usr/local/bin/*

CMD /usr/local/bin/entrypoint.sh 

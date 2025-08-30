FROM alpine:latest
MAINTAINER Ludovic MAILLET <Ludo.goodlinux@gmail.com>

ENV DB_USER=zipsme \
    DB_PASSWORD=DbPassword \
    DB_NAME=zipsme \
    DB_SERVER=192.168.10.159:3306 \
    SITE_NAME="URL Shortener" \
    SITE_URL=https://go  \
    SERVEUR_SSO=https://192.168.10.159   \
    APPID_SSO='xxxxxx'   \
    PHP_Ver=83 \
    SRV_NAME='zipme server name'   \ 
    TZ=Europe/Paris

RUN apk -U add php$PHP_Ver php$PHP_Ver-fpm php$PHP_Ver-mysqli php$PHP_Ver-curl php$PHP_Ver-session nginx git tzdata
EXPOSE 80

#Construction of redirection and php use for nginx
RUN echo "server { " > /etc/nginx/http.d/default.conf  \
    &&  echo "        listen 80 default_server; " >> /etc/nginx/http.d/default.conf	\
    &&  echo "        listen [::]:80 default_server; " >> /etc/nginx/http.d/default.conf	\
    &&  echo "        index index.php index.html index.htm; " >> /etc/nginx/http.d/default.conf	  \
    &&  echo "        root /var/www/zipsme; " >> /etc/nginx/http.d/default.conf		\
    &&  echo "		if (!-e \$request_filename){ " >> /etc/nginx/http.d/default.conf		\
    &&  echo "			rewrite ^/([A-Za-z0-9-]+)/?$ /redirect.php?url_name=\$1 break; } " >> /etc/nginx/http.d/default.conf	\
    &&  echo "    location ~ \.php$ { " >> /etc/nginx/http.d/default.conf	\
    &&  echo "        try_files \$uri =404; " >> /etc/nginx/http.d/default.conf	\
    &&  echo "        fastcgi_pass localhost:9000; " >> /etc/nginx/http.d/default.conf	\
    &&  echo "        fastcgi_index index.php; " >> /etc/nginx/http.d/default.conf	\
    &&  echo "        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; " >> /etc/nginx/http.d/default.conf		\
    &&  echo "        include fastcgi_params;    } " >> /etc/nginx/http.d/default.conf	\
    &&  echo "}  " >> /etc/nginx/http.d/default.conf

#Import project from GitHub
#RUN git clone https://github.com/Goodlinux/zipsme.git /var/www/zipsme/ && rm /var/www/zipsme/Dockerfile && touch /var/www/zipsme/error.txt
RUN git clone https://github.com/Goodlinux/zipsme.git /var/www/zipsme/ && touch /var/www/zipsme/error.txt

#Construction of entrypoint
RUN echo "#! /bin/sh" > /usr/local/bin/entrypoint.sh \
	&& echo "echo mise a jour de la config php"  >>  /usr/local/bin/entrypoint.sh  \
	&& echo "chmod a+x /var/www/zipsme/CreateConf.sh"  >>  /usr/local/bin/entrypoint.sh  \
	&& echo "/var/www/zipsme/CreateConf.sh"  >>  /usr/local/bin/entrypoint.sh  \
	&& echo "echo lancement de nginx" >> /usr/local/bin/entrypoint.sh  \
	&& echo "nginx" >> /usr/local/bin/entrypoint.sh  \
	&& echo "echo lancement de php" >> /usr/local/bin/entrypoint.sh  \
	&& echo "php-fpm$PHP_Ver" >> /usr/local/bin/entrypoint.sh  \
	&& echo "echo Timezone $TZ" >> /usr/local/bin/entrypoint.sh  \
	&& echo "cp /usr/share/zoneinfo/\$TZ /etc/localtime && echo \$TZ >  /etc/timezone" >> /usr/local/bin/entrypoint.sh \
	&& echo "cd /var/www/zipsme" >> /usr/local/bin/entrypoint.sh  \
	&& echo "echo 'Connectez vous sur le site '\$SITE_URL'/install.php pour installer la base de donnée'"  >>  /usr/local/bin/entrypoint.sh  \
	&& echo "exec /bin/sh" >> /usr/local/bin/entrypoint.sh  \
	&& chmod a+x /usr/local/bin/*

CMD /usr/local/bin/entrypoint.sh 

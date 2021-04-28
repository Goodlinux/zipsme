# INFO 
[![zipsme](https://img.shields.io/static/v1?label=based_on&message=zipsme&color=blue)](https://github.com/zipsme/zipsme)

[![alpine](https://img.shields.io/static/v1?label=using&message=alpine&color=orange)](https://alpinelinux.org)
[![php](https://img.shields.io/static/v1?label=using&message=php-8&color=orange)](link=https://www.php.net)
[![mysqli](https://img.shields.io/static/v1?label=using&message=mysqli-php&color=orange)](https://www.php.net/manual/en/class.mysqli)
[![nginx](https://img.shields.io/static/v1?label=using&message=nginx&color=orange)](https://www.nginx.com,float="left")

Create your own url shortener running on docker. 

You can find the modified sources for docker here :  
[![src](https://img.shields.io/static/v1?label=sources&message=zipsme_for_docker&color=green)](link=https://github.com/Goodlinux/zipsme)

I have update the code to use with alpine 3.13, nginx server, php8   
I have also change the mysql-php by msqli-php library in all the code
 
# INSTALL 
[![docker](https://img.shields.io/static/v1?label=docker&message=zipsme&color=green)](https://hub.docker.com/r/goodlinux/zipsme) 
 
 First create a MySQL database and MySQL user.
 Make sure the user has permission to SELECT, INSERT, UPDATE, DELETE, and CREATE. 
 Note of the database name, the database user name, and the password.
 
 Launch the container, and modify the environments variables to full fill your
 Database Name, User and Password and the others parameters
 
 You can change variables before building the container in the DockerFile, 
 then create the container whith the command line : docker build -t containername.     
 Or change the variable when you run the container, to be able to connect to your Mysql sgbd (personaly I use Mariadb) 

# ENV VARIABLES  
 DB_USER=Data Base Username  
 DB_PASSWORD=Data Base Password  
 DB_NAME=Name Of Base   
 DB_SERVER=Url of the Database server ex : 192.168.110.55:3306 or localhost:3306  
 SITE_NAME=Name of you website  
 SITE_URL=Url to access to your Site ex : http://go/ or https://My.Personal.Dns/  
 ZIPSME_ADMIN_USER=Name of the user to administrate your zipsme site  
 ZIPSME_ADMIN_PASSWORD=Password to administrate your zipsme site  
 TZ=Europe/Paris  
 
# First Launch 
 When your conatiner is alive and the envoronments variable are ok,  
 Run the install.php file in your browser. If your URL shortener is http://go/,  
 the file will be located at http://go/install.php.  

 After the install script has created the database,  
 it will display a link to the admin section (located at http://go/admin.php).  
 You can now log in with the username/password combination that you entered into 
 the ZIPSME_ADMIN_USER and  ZIPSME_ADMIN_PASSWORD variables. 

 Enjoy!

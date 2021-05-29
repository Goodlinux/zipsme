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
I have change from admin password a connection to a LDAP SERVER, each shortcut is the property of the one who created it  
You don't have to be connected to use all the shortcuts even if it has been created by someone else 
But to create a new shortcut, you have to be connected. 
To Delete or Update an existing shortcut you have to be owner of it. 
If you pass to the url http://go/xxx and xxx is not yet defined, it ask you if you want to create it. 

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
 DB_USER = Data Base Username  
 DB_PASSWORD = Data Base Password  
 DB_NAME = Name Of Base   
 DB_SERVER = Url of the Database server. ex : 192.168.110.55:3306 or localhost:3306  
 SITE_NAME = Name of you website  
 SITE_URL = Url to access to your Site (do not forget to end it with '/'). ex : http://go/ or https://My.domain.ext/  or go/
 LDAP_SRV = url of the LDAP server. ex : ldap://192.168.10.159:389 or ldap://localhost:389     
 LDAP_DOMAIN = Domain of your ldap serveur 'mydomain' in the chain 'dc=mydomain, dc=com'    
 LDAP_EXT = Extention of the domain of your LDAP 'com' in the chain 'dc=mydomain, dc=com'  
 TZ=Europe/Paris  
 
# First Launch 
 When your conatiner is alive and the envoronments variable are ok,  
 Run the install.php file in your browser. If your URL shortener is http://go/,  
 the file will be located at http://go/install.php.  

 After the install script has created the database,  
 it will display a link to the admin section (located at http://go/admin.php).  
 You can now log in with the username/password combination from your LDAP.  
 You can create, delete or update shortcuts only if you are connected to the ldap  
 but you can use the shortcuts even if you have not create them or without connecting.  

 Enjoy!

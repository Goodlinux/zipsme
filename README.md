# INFO 
  Create your own url shortener running in docker. 
  Based on the work of agussuhartono https://github.com/zipsme/zipsme
 
  You can find the sources  here : https://github.com/Goodlinux/zipsme

  I have update the code to use  with alpine 3.13 nginx server and php8  
  I have change the mysql-php by msqli-php library
 
# INSTALL 
  First create a MySQL database and MySQL user.
 
  Make sure the user has permission to SELECT, INSERT, UPDATE, DELETE, and CREATE. 
  Make note of the database name, the database user name, and the password.
 
  Launch the container, and modify the environments variables to full fill your
  Database Name, User and Password and the others parameters
 
 You can change variables creating before the container in the DockerFile, 
 then create the container docker build -t containername .    
 in Dockerfie or when you run the container, you can parameter things to be able to connect to your Mysql Sgbd (personaly I use Mariadb) 

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
  You can now log in with the username/password combination that you entered into the ZIPSME_ADMIN_USER and  ZIPSME_ADMIN_PASSWORD variables. 

  Enjoy!

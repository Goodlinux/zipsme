# INFO
Create your own url shortener running in docker
Based on the work of agussuhartono https://github.com/zipsme/zipsme

I have update the code to use  with alpine 3.13 nginx server and php8
I have change the mysql-php by msqli-php library


# INSTALL
First create a MySQL database and MySQL user.

Make sure the user has permission to SELECT, INSERT, UPDATE, DELETE, and CREATE. 
Make note of the database name, the database user name, and the password.

Launch the container, and modify the environments variables to full fill your
Database Name, User and Password and the others parameters

in Dockerfie, you can parameter things to be able to connect to your Mysql Sgbd (personaly I use Mariadb)

DB_USER=Data Base Username

DB_PASSWORD=Data Base Password

DB_NAME=Name Of Base

DB_SERVER=Url of the Database server ex : 192.168.110.55:3306 or localhost:3306

SITE_NAME=Name of you website

SITE_URL=Url to access to your Site ex : http://go/ or https://My.Personal.Dns/

ZIPSME_ADMIN_USER=Name of the user to administrate your zipsme site

ZIPSME_ADMIN_PASSWORD=Password to administrate your zipsme site

TZ=Europe/Paris


When your conatiner is alive and the envoronments variable are ok, 

Run the install.php file in your browser. If your URL shortener is http://go/, 
 the file will be located at http://go/install.php.

After the install script has created the database,  
it will display a link to the admin section (located at http://go/admin.php). 
You can now log in with the username/password combination that you entered into the ZIPSME_ADMIN_USER and  ZIPSME_ADMIN_PASSWORD variables.
Enjoy!
After the install script has created the database, it will display a link to the admin section (located at http://go/admin.php). 
You can now log in with the username/password combination that you entered into the ZIPSME_ADMIN_USER and  ZIPSME_ADMIN_PASSWORD variables.
Enjoy!



--------------------------------------------------------------------------------------------------

#Z.ips.ME
Version 1.1
Requirements: Developed for PHP 5+ / MySQL 4.1.22+, however it may work on earlier versions

--------------------------------------------------------------------------------------------------

Thanks for downloading Z.ips.ME!  Installation is pretty simple:

1. Create a MySQL database and MySQL database user on your web server.  Make sure the user has permission to SELECT, INSERT, UPDATE, DELETE, and CREATE. Make note of the database name, the database user name, and the password.
2. Open the config.php file in a text editor.  Edit the database information, site information, and admin username/password as instructed in the file.
3. Upload the entire contents of this folder to the directory on your site that you plan on using as your URL shortener.
4. Run the install.php file in your browser.  If your URL shortener is http://www.yoursite.com/zipsme/, the file will be located at http://www.yoursite.com/zipsme/install.php.  
5. After the install script has created the database, it will display a link to the admin section (located at  http://www.yoursite.com/admin.php). You can now log in with the username/password combination that you entered into the config.php file.

Enjoy!

--------------------------------------------------------------------------------------------------

Questions, comments, suggestions, bugs: email info@ips.me

Change log:

1.1
*modified header code in 301 redirect function 

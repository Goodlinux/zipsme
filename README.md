2021-03-01 
Evolution and migration to PHP 8
available also with nginx
in dev.

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

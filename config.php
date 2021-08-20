<?php
//This is where you enter in your information

define('DB_USER', $_SERVER["DB_USER"]); //Your database user name
define('DB_PASSWORD', $_SERVER["DB_PASSWORD"]); //Your database user password
define('DB_NAME', $_SERVER["DB_NAME"]); //Your database name
define('DB_SERVER', $_SERVER["DB_SERVER"]); //Url of the mysql server ex : 192.168.10.105:3306 OR localhost:3306
define('SITE_NAME', 'Your Site'); //The name of your site
define('SITE_URL', 'http://www.yoursite.com/zipsme');  //The full URL of the site where Z.ips.ME is installed (including trailing slash)
define('LDAP_SRV', 'ldap://localhost:389');  // serveur LDAP   //port du serveur ldap  ldaps == 636  lsap == 389
define('LDAP_RACINE', 'dc=domain,dc=extention');  // ldap racine


//You shouldn't need to modify anything below this.

//set true if production environment else false for development
define ('IS_ENV_PRODUCTION', true); 
//establish a connection to the database server

//include common functions
include('functions.php');

$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
if (!$DbConnect) 
   { die("Could not connect. Please make sure that you have configured the config.php file correctly : " . mysqli_error());  }

mysqli_close($DbConnect); 

//autoload classes

spl_autoload_register(function ($class) 
{ include $class . '.php';});



// set time zone to use date/time functions without warnings
date_default_timezone_set('Europe/Paris');


// configure error reporting options
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', !IS_ENV_PRODUCTION);
ini_set('error_log', 'log/phperror.txt');

?>

<?php

//This is where you enter in your information
define('ZIPSME_DB_USER', 'zipsme'); //Your database user name
define('ZIPSME_DB_PASSWORD', 'sBZ2Goq2nsTlanFz?'); //Your database mfemaesc
define('ZIPSME_DB_NAME', 'zipsme2'); //Your database name
define('ZIPSME_DB_HOST', 'localhost:3307'); //Url of the mysql server ex : 192.168.10.105:3306 or 192.168.1.10:3307:3306
define('SITE_NAME', 'URL Shortener'); //The name of your site
define('SITE_URL', 'go2/');  //The full URL of the site where Z.ips.ME is installed (including trailing slash)

define('LDAP_SRV', 'ldap://localhost:389');  // serveur LDAP   //port du serveur ldap  ldaps == 636  lsap == 389
define('LDAP_RACINE', 'dc=doamin,dc=extention');  // ldap racine

//You shouldn't need to modify anything below this.

//set true if production environment else false for development
define ('IS_ENV_PRODUCTION', true); 
//establish a connection to the database server

//echo "CONFIG --> LDAP Racine : " . LDAP_RACINE;
//echo "\n";

$DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
if (!$DbConnect) 
   { die("Could not connect. Please make sure that you have configured the config.php file correctly : " . mysqli_error());  }

mysqli_close($DbConnect); 

//autoload classes

spl_autoload_register(function ($class) 
{ include $class . '.php';});

//include common functions
include('functions.php');

// set time zone to use date/time functions without warnings
date_default_timezone_set(getenv('TZ'));


// configure error reporting options
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', !IS_ENV_PRODUCTION);
ini_set('error_log', 'log/phperror.txt');

?>

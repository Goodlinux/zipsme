<?php

// configure error reporting options
//set true if production environment else false for development

define ('IS_ENV_PRODUCTION', true); 
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', !IS_ENV_PRODUCTION);
ini_set('error_log', '/var/www/zipsme/log/phperror.txt');
// set time zone to use date/time functions without warnings
date_default_timezone_set('Europe/Paris');


//This is where you enter in your information
include('Const.php');


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


?>

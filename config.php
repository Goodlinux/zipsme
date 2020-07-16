<?php

//This is where you enter in your information
define('ZIPSME_DB_USER', 'database_user'); //Your database user name
define('ZIPSME_DB_PASSWORD', 'database_password'); //Your database password
define('ZIPSME_DB_NAME', 'database_name'); //Your database name
define('ZIPSME_DB_HOST', 'localhost'); //99% chance you won't need to change this
define ('SITE_NAME', 'Your Site'); //The name of your site
define ('SITE_URL', 'http://www.yoursite.com/zipsme/');  //The full URL of the site where Z.ips.ME is installed (including trailing slash)
define('ZIPSME_USERNAME', 'username'); //Admin username. You'll use this to log in to Z.ips.ME.  Max length 100 characters.
define('ZIPSME_PASSWORD', 'password'); //Admin password. You'll use this to log in to Z.ips.ME.  Max length 100 characters.
//You shouldn't need to modify anything below this.



//set true if production environment else false for development
define ('IS_ENV_PRODUCTION', true); 
//establish a connection to the database server
if (!$GLOBALS['DB'] = mysql_pconnect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD))
{
    die('Error: Unable to connect to database server.  Please make sure that you have configured the config.php file correctly.');
}
if (!mysql_select_db(ZIPSME_DB_NAME, $GLOBALS['DB']))
{
    mysql_close($GLOBALS['DB']);
    die('Error: Unable to select database schema.  Please make sure that you have set up your database correctly and configured it in the config.php file.');
}

//autoload classes
function __autoload($class_name) {
    require_once ($class_name . '.php');
}

//include common functions
include('functions.php');

// set time zone to use date/time functions without warnings
date_default_timezone_set('America/New_York');

// compensate for magic quotes if necessary
if (get_magic_quotes_gpc())
{
    function _stripslashes_rcurs($variable, $top = true)
    {
        $clean_data = array();
        foreach ($variable as $key => $value)
        {
            $key = ($top) ? $key : stripslashes($key);
            $clean_data[$key] = (is_array($value)) ?
            _stripslashes_rcurs($value, false) : stripslashes($value);
        }
        return $clean_data;
    }
    $_GET = _stripslashes_rcurs($_GET);
    $_POST = _stripslashes_rcurs($_POST);

}

// configure error reporting options
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', !IS_ENV_PRODUCTION);
ini_set('error_log', 'log/phperror.txt');

?>
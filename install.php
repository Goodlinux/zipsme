<?php include('config.php'); ?>
<?php

//Install script.  Run after completing config.php file
//create clicks table
$query = "CREATE TABLE IF NOT EXISTS tbl_clicks (click_id int(11) NOT NULL auto_increment,click_time datetime NOT NULL,url_name varchar(255) NOT NULL,referrer varchar(255) NOT NULL,user_agent varchar(255) NOT NULL,ip_address varchar(255) NOT NULL,  PRIMARY KEY  (click_id))";
$result = mysql_query($query,$GLOBALS['DB']);

//create links table
$query = "CREATE TABLE IF NOT EXISTS tbl_links (url_name varchar(255) NOT NULL,url text NOT NULL,type varchar(255) NOT NULL,active char(1) NOT NULL, PRIMARY KEY  (url_name))";
$result = mysql_query($query,$GLOBALS['DB']);

echo 'Z.ips.ME installed successfully!  You can now log in to your Admin page <a href="admin.php">here</a>';

?>
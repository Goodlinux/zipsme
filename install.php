<?php include('config.php'); ?>
<?php

//Install script.  Run after completing config.php file
//create clicks table
$DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);

$query = "CREATE TABLE IF NOT EXISTS tbl_clicks (click_id int(11) NOT NULL auto_increment,click_time datetime NOT NULL,url_name varchar(255) NOT NULL,referrer varchar(255) NOT NULL,user_agent varchar(255) NOT NULL,ip_address varchar(255) NOT NULL,  PRIMARY KEY  (click_id))";
$result = $DbConnect->query($query);

//create links table
$query = "CREATE TABLE IF NOT EXISTS tbl_links (url_name varchar(255) NOT NULL,url text NOT NULL,type varchar(255) NOT NULL,active char(1) NOT NULL, PRIMARY KEY  (url_name))";
$result = $DbConnect->query($query);

mysqli_close($DbConnect);

echo 'Z.ips.ME installed successfully!  You can now log in to your Admin page <a href="admin.php">here</a>';

?>

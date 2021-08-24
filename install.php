<?php include('config.php'); ?>
<?php

//Install script.  Run after completing config.php file
//create clicks table
$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

$query = "select * from tbl_links";
if (!$DbConnect->query($query)) {
    if ( $DbConnect->error == "1146" ) {
      //#1146 - La table 'xxxx' n'existe pas
	    echo  "Création de la table Links \n";
	    $query = "CREATE TABLE tbl_links (url_name varchar(255) NOT NULL, url text NOT NULL, user varchar(255), type varchar(255) NOT NULL, active char(1) NOT NULL, PRIMARY KEY  (url_name))";
	    $result = $DbConnect->query($query);
    }
}
else {
	$query = "select user from tbl_links";
	if ( !$DbConnect->query($query) ) {
		if ( $DbConnect->errno == "1054" ) {
      		//#1054 - Champ 'user' inconnu dans field list
			echo  "Ajout de la colonne 'user' \n";
			$query = "alter table tbl_links ADD COLUMN user varchar(255)";
			$result = $DbConnect->query($query);
		}
	}
}

//create links table
$query = "select * from tbl_clicks";
if (!$DbConnect->query($query)) {
	if ( $DbConnect->error == "1146" ) {
      		//#1146 - La table 'xxxx' n'existe pas
		echo "Création de la table Clicks \n";
		$query = "CREATE TABLE IF NOT EXISTS tbl_clicks (click_id int(11) NOT NULL auto_increment,click_time datetime NOT NULL,url_name varchar(255) NOT NULL,referrer varchar(255) NOT NULL,user_agent varchar(255) NOT NULL,ip_address varchar(255) NOT NULL,  PRIMARY KEY  (click_id))";
		$result = $DbConnect->query($query);
	}
}
else {
	// Rename column referrer in os 
	$query = "select referrer from tbl_clicks";
	if ( !$DbConnect->query($query) ) {
		if ( $DbConnect->errno == "1054" ) {
      		//#1054 - Champ 'referrer' inconnu dans field list
			echo  "Renommage de la colonne 'referrer' en 'os' \n";
			$query = "alter table tbl_links MODIFY COLUMN 'referrer' 'os' varchar(255)";
			$result = $DbConnect->query($query);
		}
	}
	// Rename column user_agent  in browser 
	$query = "select user_agent from tbl_clicks";
	if ( !$DbConnect->query($query) ) {
		if ( $DbConnect->errno == "1054" ) {
      		//#1054 - Champ 'user_agent' inconnu dans field list
			echo  "Renommage de la colonne 'user_agent ' en 'browser' \n";
			$query = "alter table tbl_links MODIFY COLUMN 'user_agent' 'browser' varchar(255)";
			$result = $DbConnect->query($query);
		}
	}
}

// create table users to store id for cookies purpose		       
$query = "CREATE TABLE IF NOT EXISTS users (user_id varchar(255) NOT NULL, username varchar(255) NOT NULL,  PRIMARY KEY (user_id))";
$result = $DbConnect->query($query);

mysqli_close($DbConnect);

echo 'Z.ips.ME installed successfully!  You can now log in to your Admin page <a href="admin.php">here</a>';
?>

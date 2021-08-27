<?php include('config.php'); ?>
<?php

//Install script.  Run after completing config.php file
//create clicks table
$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

$query = "select * from tbl_links";
if (!$DbConnect->query($query)) {
    if ( $DbConnect->errno == "1146" ) {
      //#1146 - La table 'xxxx' n'existe pas
	    echo  "Création de la table Links \n";
	    $query = "CREATE TABLE tbl_links (url_name varchar(255) NOT NULL, url text NOT NULL, user varchar(255), type varchar(255) NOT NULL, " . 
		    "active char(1) NOT NULL, PRIMARY KEY  (url_name))";
	    $result = $DbConnect->query($query);
    }
}
else {
	$query = "select user from tbl_links";
	if ( !$DbConnect->query($query) ) {
		if ( $DbConnect->errno == "1054" ) {
      		//#1054 - Champ 'user' inconnu dans field list
			echo  "Ajout de la colonne 'user' \n";
			$query = "ALTER TABLE tbl_links ADD COLUMN `user` varchar(255)";
			$result = $DbConnect->query($query);
		}
	}
}

//create clicks table
$query = "select * from tbl_clicks";
if (!$DbConnect->query($query)) {
	if ( $DbConnect->errno == "1146" ) {
      		//#1146 - La table 'xxxx' n'existe pas
		echo "Création de la table Clicks \n";
		$query = "CREATE TABLE IF NOT EXISTS tbl_clicks (click_id int(11) NOT NULL auto_increment,click_time datetime NOT NULL," . 
			"url_name varchar(255) NOT NULL, referrer VARCHAR(255), user_agent VARCHAR(255), os varchar(255) NOT NULL, " . 
			"browser varchar(255) NOT NULL,ip_address varchar(255) NOT NULL, PRIMARY KEY  (click_id))";
		$result = $DbConnect->query($query);
	}
}
else {
	// Add column os 
	$query = "select os from tbl_clicks";
	if ( !$DbConnect->query($query) ) {
		if ( $DbConnect->errno == "1054" ) {
      		//#1054 - Champ 'os' inconnu dans field list
			echo  "Ajout de la colonne 'os' \n";
			$query = "ALTER TABLE tbl_clicks ADD COLUMN `os` VARCHAR(255)";
			$result = $DbConnect->query($query);
		}
	}
	// Add column browser 
	$query = "select browser from tbl_clicks";
	if ( !$DbConnect->query($query) ) {
		if ( $DbConnect->errno == "1054" ) {
      		//#1054 - Champ 'user_agent' inconnu dans field list
			echo  "Ajout de la colonne 'browser' \n";
			$query = "ALTER TABLE tbl_clicks ADD COLUMN `browser` VARCHAR(255)";
			$result = $DbConnect->query($query);
		}
	}
	
	// convertir les anciens User Agent  en Browser et OS Migration des données
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT click_id, user_agent FROM tbl_clicks";
	$result = $DbConnect->query($query);
	echo "SQL : " . $DbConnect->errno . " " . $DbConnect->error;
	echo "\n";
	while ($row = mysqli_fetch_array($result)) {
        	$br = new BrowserDetection($row["user_agent"]);
        	$connect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
        	$updtquery = "UPDATE tbl_clicks SET os = '" . $br->detect()->getplatform() . "', browser = '" . $br->detect()->getBrowser() . "' WHERE click_id = '" . $row["click_id"] ."'";
        	$update = $connect->query($updtquery);

        	mysqli_close($connect);
	}
	
mysqli_close($DbConnect);
	
}

// create table users to store id for cookies purpose		       
$query = "CREATE TABLE IF NOT EXISTS users (user_id varchar(255) NOT NULL, username varchar(255) NOT NULL,  PRIMARY KEY (user_id))";
$result = $DbConnect->query($query);

mysqli_close($DbConnect);

echo 'Z.ips.ME installed successfully!  You can now log in to your Admin page <a href="admin.php">here</a>';
?>

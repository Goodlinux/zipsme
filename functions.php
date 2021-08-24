<?php

function sqlConnect() {
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	if (!$DbConnect) 
   		{ print_r("Could not connect. Please make sure that you have configured the config.php file correctly : " . mysqli_error());  }
	return $$DbConnect;
}

function prepQueryText($text) {
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$insert = $DbConnect->real_escape_string(trim($text));
	mysqli_close($DbConnect);
	return $insert;
}

function prepOutputText($text) {
	$output = htmlentities(stripslashes(nl2br($text)),ENT_QUOTES);
	return $output;
}

function redirect($url, $type='internal') {
	if (!headers_sent()) {
		if ($type == '301') {
			header("HTTP/1.1 301 Moved Permanently");
		}
		header("Location: $url");
	} else {
		echo '<script type="text/javascript">window.location = "' . $url . '"</script>';
	}
}

//function insertClick($url_name, $referrer, $user_agent, $ip_address) {
function insertClick($url_name, $osName, $browserName, $ip_address) {
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	//$query = "INSERT INTO tbl_clicks (click_time, url_name, referrer, user_agent, ip_address) VALUES (NOW(), '{$url_name}', '{$referrer}', '{$user_agent}', '{$ip_address}')";
	$query = "INSERT INTO tbl_clicks (click_time, url_name, os, browser, ip_address) VALUES (NOW(), '{$url_name}', '{$osName}', '{$browserName}', '{$ip_address}')";
	$result = $DbConnect->query($query);
	mysqli_close($DbConnect);
}

function insertLink($url_name, $url, $user, $type) {
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "INSERT INTO tbl_links (url_name, url, user, type, active) VALUES ('{$url_name}', '{$url}', '{$user}', '{$type}', 'y')";
	$result = $DbConnect->query($query);
	mysqli_close($DbConnect);
}

function getUserLink($url_name) {
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT user FROM tbl_links WHERE url_name = '{$url_name}'";
	$result = $DbConnect->query($query);
	$row = mysqli_fetch_array($result);
	return $row['user'];
	mysqli_close($DbConnect);
}

function updateLink($url_name, $url, $type) {
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "UPDATE tbl_links SET url = '{$url}', type = '{$type}' WHERE url_name = '{$url_name}'";
	$result = $DbConnect->query($query);
	mysqli_close($DbConnect);
}

function deleteLink($url_name) {
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "DELETE FROM tbl_links WHERE url_name = '{$url_name}'";
	$result = $DbConnect->query($query);
	$query = "DELETE FROM tbl_clicks WHERE url_name = '{$url_name}'";
	$result = $DbConnect->query($query);
	mysqli_close($DbConnect);
}

function linkAvailable($url_name) {
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' LIMIT 1";
	$result = $DbConnect->query($query);
	if ($result->num_rows == 0) {
		return true;
	} else {
		return false;
	}
	mysqli_close($DbConnect);
}

function getIpAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function linkExists($url_name) {
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' AND active = 'y' LIMIT 1";
	$result = $DbConnect->query($query);
	if ($result->num_rows > 0) {
		return true;
	} else {
		return false;
	}
	mysqli_close($DbConnect);
}

function redirectClick($url_name) {
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' LIMIT 1";
	$result = $DbConnect->query($query);
	$row = mysqli_fetch_array($result);
	redirect($row['url'], $row['type']);
	mysqli_close($DbConnect);
}

function stripLink($url_name) {
	$url_name = strtolower($url_name);
	$stripped = preg_replace("/[^a-zA-Z0-9]/", "", $url_name);
	return $stripped;
}

function showLinkHistory() {
	$user_connected = getUserName($_COOKIE['zipsme-user']);
	
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT tbl_links.url_name, tbl_links.url, tbl_links.user, COUNT(tbl_clicks.click_id) AS clicks 
		FROM tbl_links left join tbl_clicks ON tbl_links.url_name = tbl_clicks.url_name
		GROUP BY tbl_links.url_name ORDER BY clicks DESC";
	$result = $DbConnect->query($query);
	
	while ($row = mysqli_fetch_array($result))
	{
		echo '<td class="border">
			<a href="redirect.php?url_name=' . $row['url_name'] . '">' . SITE_URL . prepOutputText($row['url_name']) . '</a></td>' . '';
		echo '<td class="border">' . prepOutputText($row['clicks']) . '</td>' . "\n";
		echo '<td class="border">' . $row['user'] . '</td>' . "\n"; 
		if ($user_connected == $row['user']) {
			echo '<td class="border"><a href="admin.php?summary=' . $row['url_name'] . '">View Stats</a> | <a href="admin.php?edit=' . $row['url_name'] . '">Edit</a> | <a href="admin.php?pre_delete=' . $row['url_name'] . '">Delete</a>' . '</td>' . "\n";
			}
		else {
			echo '<td class="border"><a href="admin.php?summary=' . $row['url_name'] . '">View Stats</a>' . '</td>' . "\n";
		}
		echo '</tr>' . "\n";
	}			

	mysqli_close($DbConnect);
	return "x";
} 


//function searchUser($person) {
	//$ds=ldap_connect(LDAP_SRV);
//	$ds=ldap_connect($_SERVER["LDAP_SRV"]);
//	if ($ds) { 
//		$r=ldap_bind($ds, LDAP_ROOT, LDAP_PWD);   		     		 
//		$filtre="(|(sn=$person*)(cn=$person*))"; 
//		$restriction = array( "cn", "sn", "mail"); 
//		$sr=ldap_search($ds, LDAP_RACINE, $filtre, $restriction); 
//		$info = ldap_get_entries($ds, $sr); 
//		print 	$info["count"]." enregistrements trouves"; 
//	}
//	else {
//		echo "Connexion au serveur LDAP impossible";
//	}
//	ldap_close($ds);
//}

function getUserId ($username) {
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "Select user_id from users where username = '{$username}'";
	$result = $DbConnect->query($query);
	$row = mysqli_fetch_array($result);
	if ($row['user_id'] == "") {
		return false;
		}
	else {return $row['user_id'];}
	mysqli_close($DbConnect);
}

function getUserName ($userid) {
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "Select username from users where user_id = '{$userid}'";
	$result = $DbConnect->query($query);
	$row = mysqli_fetch_array($result);
	if ($row['username'] == "") {
		return false;
		}
	else {return $row['username'];}
	mysqli_close($DbConnect);
}

function existUser ($username) {
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "Select count(user_id) as userid from users where username = '{$username}'";
	$result = $DbConnect->query($query);
	$row = mysqli_fetch_array($result);
	return ($row['userid']);
	mysqli_close($DbConnect);
}


function addUser ($username) {
	if (! existUser($username)) { 
		$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$id = md5($username);                                                                                                                                                                       
                $query = "insert into users (user_id, username) VALUES ('{$id}', '{$username}')"; 
		$result = $DbConnect->query($query);
		mysqli_close($DbConnect);
	}
}

function authenticate($username, $password) { 
	$ldap_Userdn = getUserDN($username); 
	if($ldap_Userdn!="") 
	{
		$ldap_con = ldap_connect(LDAP_SRV);

		ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3); 
        	if(ldap_bind($ldap_con, $ldap_Userdn, $password)) 
			{ 
				addUser($username);
				return true; 
			} 
		else { return false; }
	}
    	else { echo "Error to find user DN" . ldap_error($ldap_con); }
	ldap_close($ldap_con);
}

function getUserDN($username)
{ 
	$data = ""; 
	$ldap_con = ldap_connect(LDAP_SRV); 
	if (substr(LDAP_SRV,0,4) == "ldap") {                                                                                                                                                            
                $srv = LDAP_SRV;}                                                                                                                                                                           
        else {                                                                                                                                                                                              
                $srv = "ldaps://" . LDAP_SRV;                                                                                                                                                               
        }                                                                                                                                                                                                   
 	$ldap_con = ldap_connect($srv); 

	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3); 
	ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0); 
    	
   	$filter="(cn=$username)"; 
	$res = ldap_search($ldap_con, LDAP_RACINE, $filter); 
	$first = ldap_first_entry($ldap_con, $res); 
	$data = ldap_get_dn($ldap_con, $first);
   	ldap_close($ldap_con); 
	return $data;
}

?>

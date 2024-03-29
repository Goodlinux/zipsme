<?php

function sqlConnect() {
	if (! IS_ENV_PRODUCTION) { echo "Function-->sqlConnect \n"; }
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
	if (! IS_ENV_PRODUCTION) { echo "Function-->redirect \n"; }
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
function insertClick($url_name, $referrer, $user_agent, $osName, $browserName, $ip_address) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->insertClick \n"; 	}
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	//$query = "INSERT INTO tbl_clicks (click_time, url_name, referrer, user_agent, ip_address) VALUES (NOW(), '{$url_name}', '{$referrer}', '{$user_agent}', '{$ip_address}')";
	$query = "INSERT INTO tbl_clicks (click_time, url_name, referrer, user_agent, os, browser, ip_address) " . 
		"VALUES (NOW(), '{$url_name}', '{$referrer}', '{$user_agent}', '{$osName}', '{$browserName}', '{$ip_address}')";
	$result = $DbConnect->query($query);
	mysqli_close($DbConnect);
}

function insertLink($url_name, $url, $user, $type) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->insertLink \n"; }
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "INSERT INTO tbl_links (url_name, url, user, type, active) VALUES ('{$url_name}', '{$url}', '{$user}', '{$type}', 'y')";
	$result = $DbConnect->query($query);
	mysqli_close($DbConnect);
}

function getUserLink($url_name) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->getUserLink \n"; }
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT user FROM tbl_links WHERE url_name = '{$url_name}'";
	$result = $DbConnect->query($query);
	$row = mysqli_fetch_array($result);
	return $row['user'];
	mysqli_close($DbConnect);
}

function updateLink($url_name, $url, $type) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->updateLink \n"; }
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "UPDATE tbl_links SET url = '{$url}', type = '{$type}' WHERE url_name = '{$url_name}'";
	$result = $DbConnect->query($query);
	mysqli_close($DbConnect);
}

function deleteLink($url_name) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->deleteLink \n"; }
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "DELETE FROM tbl_links WHERE url_name = '{$url_name}'";
	$result = $DbConnect->query($query);
	$query = "DELETE FROM tbl_clicks WHERE url_name = '{$url_name}'";
	$result = $DbConnect->query($query);
	mysqli_close($DbConnect);
}

function linkAvailable($url_name) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->linkAvailable \n"; }
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
	if (! IS_ENV_PRODUCTION) { echo "Function-->getIpAddress \n"; }
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
	if (! IS_ENV_PRODUCTION) { echo "Function-->linkExists \n"; }
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
	if (! IS_ENV_PRODUCTION) { echo "Function-->redirectClick \n"; }
	$url_name = strtolower($url_name);
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' LIMIT 1";
	$result = $DbConnect->query($query);
	$row = mysqli_fetch_array($result);
	redirect($row['url'], $row['type']);
	mysqli_close($DbConnect);
}

function stripLink($url_name) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->stripLink \n"; }
	$url_name = strtolower($url_name);
	$stripped = preg_replace("/[^a-zA-Z0-9]/", "", $url_name);
	return $stripped;
}

function showLinkHistory() {
	if (! IS_ENV_PRODUCTION) { echo "Function-->showLinkHistory \n"; }
	$user_connected = getUserName($_COOKIE['zipsme-user']);
	
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT tbl_links.url_name, tbl_links.url, tbl_links.user, COUNT(tbl_clicks.click_id) AS clicks 
		FROM tbl_links left join tbl_clicks ON tbl_links.url_name = tbl_clicks.url_name
		GROUP BY tbl_links.url_name ORDER BY clicks DESC";
	$result = $DbConnect->query($query);
	
	while ($row = mysqli_fetch_array($result))
	{
		echo '<td class="border">
			<a href="redirect.php?url_name=' . $row['url_name'] . '">' . prepOutputText($row['url_name']) . '</a></td>' . '';
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


function getUserId($username) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->getUserId \n"; }
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

function getUserName($userid) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->getUserName \n"; }
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

function existUser($username) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->existUser \n"; }
	$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "Select count(user_id) as userid from users where username = '{$username}'";
	$result = $DbConnect->query($query);
	$row = mysqli_fetch_array($result);
	return ($row['userid']);
	mysqli_close($DbConnect);
}


function addUser($username) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->addUser \n"; }
	if (! existUser($username)) { 
		$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$id = md5($username);
                $query = "insert into users (user_id, username) VALUES ('{$id}', '{$username}')"; 
		$result = $DbConnect->query($query);
		mysqli_close($DbConnect);
	}
}


function sso_authenticate($username) {
		if (! IS_ENV_PRODUCTION) { echo "Function-->sso_authenticate \n"; }
		addUser($username);
}


function authenticate($username, $password) {
	if (! IS_ENV_PRODUCTION) { echo "Function-->authenticate \n"; }
		$ldap_Userdn = "uid=" . $username . "," . LDAP_RACINE;
		$ldap_con = ldap_connect(LDAP_SRV); 
		if (! IS_ENV_PRODUCTION) { echo "LDAP-con-Result: " . ldap_errno($ldap_con) . " "  . ldap_error($ldap_con) . "\n"; } 
			if($ldap_Userdn != "")
				{
				ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);      
				ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
				$bind = ldap_bind($ldap_con, $ldap_Userdn, $password);
				if (! IS_ENV_PRODUCTION) { echo "LDAP-bind-Result: " . ldap_errno($ldap_con) . " "  . ldap_error($ldap_con) . "\n"; }
					if($bind != "")
						{
						if (! IS_ENV_PRODUCTION) {  echo "Function-->authenticate Password Ok \n";  }
							addUser($username);
							return true;
						}
						else {
							if (! IS_ENV_PRODUCTION) { echo "Function-->authenticate Password Ko \n";   }
							return false;
						}
				}
				else {
					if (! IS_ENV_PRODUCTION) { echo "Function-->authenticate User does not exist \n";   } 
					return false;
	}
	ldap_close($ldap_con);
}


?>

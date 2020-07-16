<?php

function prepQueryText($text) {
	$insert = mysql_real_escape_string(trim($text));
	return $insert;
}

function prepOutputText($text) {
	$output = htmlentities(stripslashes(nl2br($text)),ENT_QUOTES);
	return $output;
}

function redirect($url, $type) {
	if (!headers_sent()) {
		if ($type == '301') {
			header("301 Moved Permanently HTTP/1.1");
		}
		header("location: $url");
	} else {
		echo '<script type="text/javascript">window.location = "' . $url . '"</script>';
	}
}

function insertClick($url_name, $referrer, $user_agent, $ip_address) {
	$query = "INSERT INTO tbl_clicks (click_time, url_name, referrer, user_agent, ip_address) VALUES (NOW(), '{$url_name}', '{$referrer}', '{$user_agent}', '{$ip_address}')";
	$result = mysql_query($query,$GLOBALS['DB']);
}

function insertLink($url_name, $url, $type) {
	$query = "INSERT INTO tbl_links (url_name, url, type, active) VALUES ('{$url_name}', '{$url}', '{$type}', 'y')";
	$result = mysql_query($query,$GLOBALS['DB']);
}

function updateLink($url_name, $url, $type) {
	$query = "UPDATE tbl_links SET url = '{$url}', type = '{$type}' WHERE url_name = '{$url_name}'";
	$result = mysql_query($query,$GLOBALS['DB']);
}

function deleteLink($url_name) {
	$query = "DELETE FROM tbl_links WHERE url_name = '{$url_name}'";
	$result = mysql_query($query,$GLOBALS['DB']);
	$query = "DELETE FROM tbl_clicks WHERE url_name = '{$url_name}'";
	$result = mysql_query($query,$GLOBALS['DB']);
	
}

function linkAvailable($url_name) {
	$query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' LIMIT 1";
	$result = mysql_query($query,$GLOBALS['DB']);
	$row = mysql_fetch_assoc($result);
	$num = mysql_num_rows($result);
	if ($num == 0) {
		return true;
	} else {
		return false;
	}
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
	$query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' AND active = 'y' LIMIT 1";
	$result = mysql_query($query,$GLOBALS['DB']);
	$num = mysql_num_rows($result);
	if ($num == 1) {
		return true;
	} else {
		return false;
	}
}

function redirectClick($url_name) {
	$query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' LIMIT 1";
	$result = mysql_query($query,$GLOBALS['DB']);
	$row = mysql_fetch_assoc($result);
	redirect($row['url'], $row['type']);
}

function stripLink($url_name) {
	$stripped = preg_replace("/[^a-zA-Z0-9]/", "", $url_name);
	return $stripped;
}

function showLinkHistory() {
	$query = "SELECT *, COUNT(tbl_clicks.click_id) AS totalCount FROM tbl_clicks, tbl_links WHERE tbl_clicks.url_name = tbl_links.url_name GROUP BY tbl_links.url_name ORDER BY totalCount DESC";
	$result = mysql_query($query,$GLOBALS['DB']);
	$row = mysql_fetch_assoc($result);
	$num = mysql_num_rows($result);	
	
	$query_zero = "SELECT *,tbl_links.url_name AS noClick FROM tbl_clicks, tbl_links WHERE tbl_links.url_name NOT IN (SELECT tbl_clicks.url_name FROM tbl_clicks WHERE tbl_clicks.url_name = tbl_links.url_name) GROUP BY noClick";
	$result_zero = mysql_query($query_zero,$GLOBALS['DB']);
	$row_zero = mysql_fetch_assoc($result_zero);
	$num_zero = mysql_num_rows($result_zero);	
	
	if (($num > 0) || ($num_zero > 0)) {
		if ($num > 0) {
			do {
				echo '<tr>' . "\n";
				echo '<td class="border">' . SITE_URL . prepOutputText($row['url_name']) . '</td>' . "\n";
				echo '<td class="border">' . prepOutputText($row['totalCount']) . '</td>' . "\n";
				echo '<td class="border"><a href="admin.php?summary=' . $row['url_name'] . '">View Stats</a> | <a href="admin.php?edit=' . $row['url_name'] . '">Edit</a> | <a href="admin.php?pre_delete=' . $row['url_name'] . '">Delete</a>' . '</td>' . "\n";	
				echo '</tr>' . "\n";
			} while ($row = mysql_fetch_assoc($result));			
		}
		if ($num_zero > 0) {
			do {
				echo '<tr>' . "\n";
				echo '<td class="border">' . SITE_URL . prepOutputText($row_zero['noClick']) . '</td>' . "\n";
				echo '<td class="border">0</td>' . "\n";
				echo '<td class="border"><a href="admin.php?summary=' . $row_zero['noClick'] . '">View Stats</a> | <a href="admin.php?edit=' . $row_zero['noClick'] . '">Edit</a> | <a href="admin.php?pre_delete=' . $row_zero['noClick'] . '">Delete</a>' . '</td>' . "\n";	
				echo '</tr>' . "\n";
			} while ($row_zero = mysql_fetch_assoc($result_zero));			
		}		
	} else {
		echo '<tr>' . "\n";
		echo '<td class="border">None</td>' . "\n";
		echo '<td class="border">&nbsp;</td>' . "\n";
		echo '<td class="border">&nbsp;</td>' . "\n";	
		echo '</tr>' . "\n";			
	}
}
?>
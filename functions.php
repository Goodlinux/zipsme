<?php 

function prepQueryText($text) 
{ $insert = mysqli_real_escape_string($Dbconnect, trim($text)); 
  return $insert;
}

function prepOutputText($text) 
{ 
 $output = htmlentities(stripslashes(nl2br($text)), ENT_QUOTES); 
 return $output;
}

function redirect($url, $type = 'internal') 
{ if (!headers_sent()) 
    { if ($type == '301') 
	{ header('HTTP/1.1 301 Moved Permanently');    }
        header("Location: {$url}");
    } else {
        echo '<script type="text/javascript">window.location = "' . $url . '"</script>';
    }
}

function insertClick($url_name, $referrer, $user_agent, $ip_address) 
{ 
  $DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
  $query = "INSERT INTO tbl_clicks (click_time, url_name, referrer, user_agent, ip_address) VALUES (NOW(), 
     '{$url_name}', '{$referrer}', '{$user_agent}', '{$ip_address}')"; 
  $result =  $DbConnect->query($query);
  mysqli_close($DbConnect);
}

function insertLink($url_name, $url, $type) 
{ 
    $DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
    $query = "INSERT INTO tbl_links (url_name, url, type, active) VALUES ('{$url_name}',  '{$url}', '{$type}', 'y')"; 
    $result = $DbConect->query($query);
    mysqli_close($DbConnect);
}

function updateLink($url_name, $url, $type) 
{ 
    $DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
    $query = "UPDATE tbl_links SET url = '{$url}', type = '{$type}' WHERE url_name =  '{$url_name}'"; 
    $result = $DbConnect->query($query);
    mysqli_close($DbConnect);
}

function deleteLink($url_name) 
{ 
    $DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
    $query = "DELETE FROM tbl_links WHERE url_name = '{$url_name}'"; 
    $result = $DbConnect->query($query); 
    $query = "DELETE FROM tbl_clicks WHERE url_name = '{$url_name}'"; 
    $result = $DbConnect->query($query);
    mysqli_close($DbConnect);
}

function linkAvailable($url_name) 
{ 
    $DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
    $query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' LIMIT 1"; 
    $result = $DbConnect->query($query); 
    $row = mysqli_fetch_assoc($result); 
    $num = mysqli_num_rows($result); 
    if ($num == 0) {
        return true;  } 
    else {
        return false;
    }
    mysqli_close($DbConnect);
}

function getIpAddress() 
{ 
if (!empty($_SERVER['HTTP_CLIENT_IP'])) { $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    return $ip;
}

function linkExists($url_name) 
{ 
    $DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
    $query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' AND active = 'y' LIMIT 1"; 
    $result = $DbConnect->query($query); 
    $num = mysqli_num_rows($result); 
        if ($num == 1) {
           return true;
        } else {
           return false;
        }
    mysqli_close($DbConnect);
}

function redirectClick($url_name) 
{ 
    $DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
    $query = "SELECT * FROM tbl_links WHERE url_name = '{$url_name}' LIMIT 1"; 
    $result = $DbConnect->query($query); 
    $row = mysqli_fetch_assoc($result); 
    redirect($row['url'], $row['type']);
    mysqli_close($DbConnect);
}

function stripLink($url_name) 
{ 
    $stripped = preg_replace('/[^a-zA-Z0-9]/', '', $url_name); return $stripped;
}

function showLinkHistory() 
{ 
    $DbConnect = mysqli_connect(ZIPSME_DB_HOST, ZIPSME_DB_USER, ZIPSME_DB_PASSWORD, ZIPSME_DB_NAME);
    $query = 'SELECT *, COUNT(tbl_clicks.click_id) AS totalCount FROM tbl_clicks, tbl_links WHERE 
                   tbl_clicks.url_name = tbl_links.url_name GROUP BY tbl_links.url_name ORDER BY totalCount DESC'; 
    $result = $DbConnect->query($query); 
    $row = mysqli_fetch_assoc($result); 
    $num = mysqli_num_rows($result); 
    $query_zero = 'SELECT *,tbl_links.url_name AS noClick FROM tbl_clicks, tbl_links WHERE tbl_links.url_name NOT IN 
         (SELECT tbl_clicks.url_name  FROM tbl_clicks WHERE tbl_clicks.url_name = tbl_links.url_name) GROUP BY noClick'; 
    $result_zero = $DbConect->query($query_zero); 
    $row_zero = mysqli_fetch_assoc($result_zero); 
    $num_zero = mysqli_num_rows($result_zero); 
    if ($num > 0 ||  $num_zero > 0) {
        if ($num > 0) { 
              do { echo '<tr>' . ' '; echo '<td class="border">' . SITE_URL . prepOutputText($row['url_name']) . '</td>' . ''; 
		   echo '<td class="border">' . prepOutputText($row['totalCount']) . '</td>' . ' '; 
		   echo '<td class="border"><ahref="admin.php?summary=' . $row['url_name'] . '">View Stats</a> | 
                            <a href="admin.php?edit=' . $row['url_name'] .'">Edit</a> | 
			    <a href="admin.php?pre_delete=' . $row['url_name'] . '">Delete</a>' . '</td>' . ''; 
		   echo '</tr>' . ' ';
            	} 
		while ($row = mysqli_fetch_assoc($result));
        }
        if ($num_zero > 0) 
		{ do {  echo '<tr>' . ' '; 
			echo '<td class="border">' . SITE_URL . prepOutputText($row_zero['noClick']) . '</td>' . ''; 
			echo '<td class="border">0</td>' . ' '; 
			echo '<td class="border"><a href="admin.php?summary=' . $row_zero['noClick'] . '">View  Stats</a> | 
				<a href="admin.php?edit=' . $row_zero['noClick'] . '">Edit</a> | 
				<a href="admin.php?pre_delete=' . $row_zero['noClick'] . '">Delete</a>' . '</td>' . ''; 
			echo '</tr>' . ' ';
            	} 
		while ($row_zero = mysqli_fetch_assoc($result_zero));
        }
    } else {
        echo '<tr>' . ' '; 
	echo '<td class="border">None</td>' . ' '; 
	echo '<td class="border"> </td>' . ' '; 
	echo '<td class="border"> </td>' . ''; 
	echo '</tr>' . ' ';
    }
    mysqli_close($DbConnect);
}
?>

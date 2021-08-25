<?php 
include('config.php'); 

$url_name = prepQueryText($_GET['url_name']);

if (linkExists($url_name)) {
	// name exist, so add a new click for stats
	$referrer = $_SERVER['HTTP_REFERER'];
	$browserInfo = new BrowserDetection();
	$browserName = $browserInfo->detect()->getBrowser();
	$osName = $browserInfo->detect()->getPlatform();
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$ip_address = getIpAddress();	
//	echo $url_name . ":" . $referrer . ":" . $user_agent . ":" .  $ip_address;
	echo $url_name . ":" . $osName . ":" . $browserName . ":" .  $ip_address;
//	insertClick($url_name, $referrer, $user_agent, $ip_address);
	insertClick($url_name, $osName, $browserName, $ip_address);
	
	redirectClick($url_name);
} else {
	// propose to create a new link
	echo '<script type="text/javascript">window.location = "admin.php?newlink=' . $url_name .'" </script>';
}

?>

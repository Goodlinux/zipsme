<?php 
include('config.php'); 

$url_name = prepQueryText($_GET['url_name']);

if (linkExists($url_name)) {
	$referrer = $_SERVER['HTTP_REFERER'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$ip_address = getIpAddress();	
//	echo $url_name . ":" . $referrer . ":" . $user_agent . ":" .  $ip_address;
	insertClick($url_name, $referrer, $user_agent, $ip_address);
	redirectClick($url_name);
} else {
	echo '<script type="text/javascript">window.location = "admin.php?newlink=' . $url_name .'" </script>';
}
?>

<?php include('config.php'); ?>

<?php 
// sso login backend to retrieve username
session_start(); 
$accesstoken = $_GET['accesstoken']; 

if (! IS_ENV_PRODUCTION) { echo "accesstoken : " . $accesstoken . " \n";}

function httpGet ($url) 
{ 
	echo "Fonction httpGet \n";
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	//for testing, ignore checking CA 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	$output=curl_exec($ch); 
	curl_close($ch); 
	echo "output : " . $output . " \n";
	return $output;
}

$url_str = SERVEUR_SSO . "/webman/sso/SSOAccessToken.cgi?action=exchange&access_token=" . $accesstoken;

if (! IS_ENV_PRODUCTION) { echo "url : " . $url_str . " \n" ;}

$resp = httpGet($url_str); 
$json_resp = json_decode($resp, true); 
if (! IS_ENV_PRODUCTION) { echo "Reponse : " . $json_resp . " \n" ;}

if($json_resp["success"] == true){ 
	$userid = $json_resp["data"]["user_id"]; 
	$username = $json_resp["data"]["user_name"];
	if (! IS_ENV_PRODUCTION) { 	echo "user : " . $userid . " - " . $username . " \n" ;}	
	header("Refresh:0; url=admin.php?logged=y&username=" . $username);
	//login success
	} 
else {
	//setcookie('zipsme-user', '');
	//setcookie('zipsme-login', 'n');
	if (! IS_ENV_PRODUCTION) { echo "logout"; }
	header("Refresh:0; url=admin.php");
}

?>

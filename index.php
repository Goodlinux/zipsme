<?php include('config.php'); ?>
<?php 
	//init cookies
	if ( ! isset($COOKIE['zipsme-user']) ) {
		setcookie('zipsme-user', '');
	}
	if ( ! isset($COOKIE['zipsme-login']) ) {
		setcookie('zipsme-login', 'no');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="index,follow" />
		<link type="text/css" rel="stylesheet" href="reset-fonts-grids.css" />
		<link type="text/css" rel="stylesheet" href="styles.css" />
		<title><?php echo SITE_NAME; ?> - Powered by Z.ips.ME</title>
	</head>
	<META HTTP-EQUIV=Refresh CONTENT="0; URL=admin.php">
</html>

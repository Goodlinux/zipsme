<?php include('config.php'); ?>
<?php
	// Version 2.4 change way to calculate Stats store directly in the Database the browser name and os name to facilitate stats
	// Version 2.3 changes environment variables for docker env and take changes after each start/stop of the container in case a parameter had change
	// logout asked logout

  // login buton submited, check if the user exist in Ldap
	if ( isset($_POST['login_submitted']) && $_POST['username'] != ""  )  {
		if ( authenticate($_POST['username'],$_POST['password']) ) {
			//setcookie('zipsme-user', $_POST['username']);
			setcookie('zipsme-user', md5($_POST['username']));
			setcookie('zipsme-login', 'y');
			$logged_in = 'y'; 
			header("Refresh:0; url=admin.php");
		}
		else {
			setcookie('zipsme-user', '');
			setcookie('zipsme-login', 'n');
			$alert = 'Connexion error, username or password is not valid';
			$logged_in = 'n'; 
			$view = 'login';
		}
	
	
	$logged_in = $_COOKIE['zipsme-login'];
	$user = getUserName($_COOKIE['zipsme-user']);
	//echo "Connected user : " . $logged_in . " | user : '" . $user . "'";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="index,follow" />
<link rel="icon" type="image/x-icon" href="/Go.ico" />
<link rel="shortcut icon" type="image/x-icon" href="/Go.ico" />
<link type="text/css" rel="stylesheet" href="reset-fonts-grids.css" />
<link type="text/css" rel="stylesheet" href="styles.css" />
<title><?php echo SITE_NAME; ?> - Powered by Z.ips.ME</title>
</head>
<body>
	<center>
    <div id="container">
        <div id="header"><h1><?php echo SITE_NAME; ?></h1></div>
        <div id="content">
 <!-- Alert -->
        <?php if (isset($alert)) { ?><p class="alert"><?php echo $alert; ?></p><?php } ?>
 <!-- Logged in -->
            <?php if ( $_COOKIE['zipsme-login'] == 'y' ) { ?>
            	<div id="logout-link"><a href="admin.php">Home</a> | <a href="admin.php?logout=y"><?php echo $user; ?> Log Out</a></div>
            <?php } else { ?>
            	<div id="login-link"><a href="admin.php">Home</a> | <a href="login.php?login=y">Log In</a></div>
            <?php }  ?>

       				<h2>Login</h2>
           			<form action="admin.php" method="post" id="login-form">
           				<label>Username:</label><input type="text" maxlength="100" name="username" /><br />
           				<label>Password:</label><input type="password" maxlength="100" name="password" /><br />
               			<input type="hidden" value="1" name="login_submitted"/>
               			<input type="submit" value="Log In" id="form-button"/>
           			</form>
       </div>
        <div id="footer">Powered by <a href="http://z.ips.me">Z.ips.ME</a>      we using cookies for connection purpose only</div>
	</div>
    </center>
</body>
</html>

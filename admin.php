<?php include('config.php'); ?>
<?php
	// Version 2.4 change way to calculate Stats store directly in the Database the browser name and os name to facilitate stats
	// Version 2.3 changes environment variables for docker env and take changes after each start/stop of the container in case a parameter had change
	// logout asked logout
	if ((isset($_GET['logout'])) && ($_GET['logout'] == 'y') && ($_COOKIE['zipsme-login'] == 'y')) {
		setcookie('zipsme-login', 'n');
		setcookie('zipsme-user', '');
		$logged_in = 'n';
		$user = '';
		$alert = 'You\'ve successfully logged out';
		header("Refresh:0; url=admin.php");
	} 

	// login buton submited, check if the user exist in Ldap
	if ( (isset($_GET['logged']) && ($_GET['logged'] == 'y')) && $_GET['username'] != ""  )  {
		sso_authenticate($_GET['username']);
		//setcookie('zipsme-user', $_POST['username']);
		setcookie('zipsme-user', md5($_GET['username']));
		setcookie('zipsme-login', 'y');
		$logged_in = 'y'; 
		//$user = $_POST['username'];
		header("Refresh:0; url=admin.php");
	}
	
	// demande de login change view to login
	//if ((isset($_GET['login'])) && ($_GET['login'] == 'y')) {
	//	$logged_in = 'n';
	//	$alert = 'Login in';
	//	$view = 'login';
	//	header("Refresh:1; url=admin.php");
	//} 
	
	if (isset($_POST['url_submitted'])) {
		if ($_COOKIE['zipsme-login'] == 'y') {
			$url = prepQueryText($_POST['url']);
			$url_name = prepQueryText($_POST['url_name']);
			$url_name = stripLink($url_name);
			//$user = $_COOKIE['zipsme-user'];
			$user = getUserName($_COOKIE['zipsme-user']);
			$type = '301';    // $_POST['type'];
			if (linkAvailable($url_name)) {
				insertLink($url_name, $url, $user, $type);
				$alert = 'Link created successfully! <a target="_blank" href="'. $url_name . '">' . $url_name . '</a> now redirects to ' . $url;		
				}
			else {
				$alert = 'The link name ' . $url_name . ' is already being used.  Try a different name or edit the existing link';
			}
		}
		else {
			$alert = 'You must be login to create a new url';
		}
	}
	
	if (isset($_POST['edit_submitted'])) {
		if ($_COOKIE['zipsme-login'] == 'y') {
			$url_name = prepQueryText($_POST['url_name']);
			if (getUserLink($url_name) == getUserName($_COOKIE['zipsme-user'])) {
				$url = prepQueryText($_POST['url']);
				$type = '301';     //$_POST['type'];
				updateLink($url_name, $url, $type);
				$alert = 'Update successful!';
				}
			else {
				$alert = $url_name . ' link belong to ' . getUserLink($url_name) . ', your are not athorized to edit it';
			}
		}
		else {
			$alert = 'You must be login to edit url';
		}
	}
	
	if (isset($_GET['summary'])) { 
		$url_name = prepQueryText($_GET['summary']);
		$summary = new Stats($url_name);
		$view = 'stats';
	}
	
	if (isset($_GET['edit'])) { 
		$url_name = prepQueryText($_GET['edit']);
		$edit = new Info($url_name);
		$view = 'edit';
	}
	
	if (isset($_GET['newlink'])) {
		$url_name = prepQueryText($_GET['newlink']);
		$alert = $url_name . ' do not exist. Do you Want to shorten it ?';
		$view = 'url-form';
	}

	if (isset($_GET['delete'])) {
		if ($_COOKIE['zipsme-login'] == 'y') {
			$url_name = prepQueryText($_GET['delete']);
			if (getUserLink($url_name) == getUserName($_COOKIE['zipsme-user'])) {
				deleteLink($url_name);
				$alert = $url_name . ' has been permanently deleted.';
				}
			else {
				$alert = $url_name . ' link belong to ' . getUserLink($url_name) . ', your are not athorized to delete it';
			}
		}
		else {
			$alert = 'You must be login to delete url';
		}	
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
	<!-- include Synology SSO js -->
	<script src='<?php echo SERVEUR_SSO; ?>/webman/sso/synoSSO-1.0.0.js'></script>
	<title><?=$SITE_NAME; ?> - Powered by Z.ips.ME</title>
</head>
<body>
	<script>
		//function setButton (logged) {
		//	if (logged) {
		//		document.getElementById('login_btn').innerHTML = '<button onclick="SYNOSSO.logout()">Logout</button>';
		//	} else {
		//		document.getElementById('login_btn').innerHTML = '<button onclick="SYNOSSO.login()">Login</button>';
		//	} 
		//}
		function logout() {
			console.log("login out");
			//SYNOSSO.logout();
			window.location.href = "admin.php?logout=y"
		}
		function login() {
			console.log("login in");
			SYNOSSO.login();
		}
		function authCallback(reponse) {
			console.log(JSON.stringify(reponse));
			if (reponse.status == 'login') {
				console.log('logged');
				token = reponse.access_token;
				console.log('Token : ' + token);
				//setButton(true);
				window.location.href = "login_backend.php?accesstoken=" + reponse.access_token;
			}
			else {
				console.log('not logged ' + reponse.status);
				//setButton(false);
				//window.location.href = "admin.php?logout=y";
				//window.location.href = "admin.php";
				}
		}
		SYNOSSO.init({
			oauthserver_url: '<?php echo SERVEUR_SSO; ?>',
			app_id: '<?php echo APPID_SSO; ?>',
			redirect_uri: '<?php echo SITE_URL; ?>',
			callback: authCallback
		});
	</script>
	<center>
		<div id="container">
		<div id="header"><h1><?php echo SITE_NAME; ?></h1></div>
		<div id="content">
<!-- Alert -->
		<?php if (isset($alert)) { ?><p class="alert"><?php echo $alert; ?></p><?php } ?>

<!-- Logged in button -->
		<?php if ( $_COOKIE['zipsme-login'] == 'y' ) { ?>
			<button onclick="window.location.href = 'admin.php'" id="home">Home</button> | <button id="login_btn" onclick="logout()">Logout <?=$user; ?></button>
		<?php } else { ?>
			<button onclick="window.location.href = 'admin.php'" id="home">Home</button> | <button id="login_btn" onclick="login()">Login</button>
		<?php }  ?>	
<!--		<button onclick="window.location.href = 'admin.php'" id="home">Home</button> | <button id="login_btn">Login</button> -->

<!-- Pre delet -->
		<?php if (isset($_GET['pre_delete'])) { ?>
			<p class="alert">Are you sure you want to delete the link <strong><?php echo SITE_URL; ?>/<?php echo prepOutputText($_GET['pre_delete']) ?></strong> ?  
			<a href="admin.php?delete=<?php echo prepOutputText($_GET['pre_delete']) ?>">Yes</a> | <a href="admin.php">No</a></p>
		<?php } ?>
  
<!-- Affichage des vues --> 
		<?php switch ($view): 
			case 'stats':   ?>
				<h2>Statistics for <strong><?php echo $summary->url_name; ?></strong></h2>
				<h2>Target url : <strong><?php echo $summary->url; ?></strong></h2>
				<?php if ($summary->total_clicks > 0) { ?>
					<div id="click-summary" align="left">
						<h3>Clicks</h3>                    
						<table cellspacing="0" cellpadding="2" border="0" class="border"><tbody>
						<tr>
							<td class="border"><strong>Month</strong></td>
							<td class="border"><strong>Clicks</strong></td>
						</tr>
						<?php $summary->showClicks(); ?>
						</tbody></table>

						<h3>OS & Browser</h3>
						<table cellspacing="0" cellpadding="2" border="0" class="border"><tbody>
						<tr>
							<td class="border"><strong>Os : Browser</strong></td>
							<td class="border"><strong>Clicks</strong></td>
						</tr>
						<?php $summary->showOsBrowsers() ; ?>
						</tbody></table>
						
						<h3>Browsers</h3>
						<table cellspacing="0" cellpadding="2" border="0" class="border"><tbody>
						<tr>
							<td class="border"><strong>Browser</strong></td>
							<td class="border"><strong>Clicks</strong></td>
						</tr>
						<?php $summary->showBrowsers(); ?>
						</tbody></table> 
						
						<h3>Operating Systems</h3>  
						<table cellspacing="0" cellpadding="2" border="0" class="border"><tbody>
						<tr>
							<td class="border"><strong>Operating System</strong></td>
							<td class="border"><strong>Clicks</strong></td>
						</tr>
						<?php $summary->showOs(); ?>
						</tbody></table>

					</div>
				<?php } else { ?>
					<p>No clicks yet!</p>
					<?php } ?>
					<?php break; ?>
  <!-- Edit -->	
			<?php case 'edit':   ?>
				<h2>Edit <strong><?php echo $edit->url_name; ?></strong></h2>
					<form action="admin.php" method="post" id="url-form">
						<label>Original Link</label><input type="text" name="url" size="50" value="<?php echo $edit->url; ?>" /><br /> 
						<input type="hidden" value="1" name="edit_submitted"/>
						<input type="hidden" value="<?php echo $edit->url_name; ?>" name="url_name"/>
						<input type="submit" value="Update" id="form-button"/>
					</form>
					<?php break; ?>
 <!-- Login -->	 
			<?php case 'login':   ?>
				<h2>Login</h2>
				<form action="log_front.html" method="post">
					<input type="hidden" value="1" name="login_submitted"/>
					<input type="submit" value="Log In" id="form-button"/>
				</form>
				<?php break; ?>
				</center></body> 
			<?php default:   ?>   
				<h2>Shorten a New Link</h2>
					<form action="admin.php" method="post" id="url-form">
						<label>Original Link</label><input type="text" name="url" size="50" /><br />
						<label>New Link Name</label><input maxlength="255" type="text" name="url_name" value="<?php echo $url_name; ?>" /><br />
						<input type="hidden" value="1" name="url_submitted"/>
						<input type="submit" value="Shorten It!" id="form-button"/>
					</form>
				<h2>Link History</h2>
					<table cellspacing="0" cellpadding="0" border="0" width="100%" class="border"><tbody>	
						<tr>
							<td class="border"><strong>Link Name</strong></td>
							<td class="border"><strong>Clicks</strong></td>
							<td class="border"><strong>User</strong></td>
							<td class="border"><strong>Options</strong></td>
						</tr>
						<?php showLinkHistory(); ?>
					</tbody></table>
					<?php break; ?>
	<!-- End Case -->
			<?php  endswitch;  ?>
		</div>
		<div id="footer">Derived from <a href="http://z.ips.me">Z.ips.ME</a> <?php echo SRV_NAME; ?>  we use cookies for connection purpose only</div>
		</div>
	</center>
</body>
</html>

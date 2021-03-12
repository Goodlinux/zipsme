<?php include('config.php'); ?>
<?php
if ( ((isset($_POST['login_submitted'])) && ($_POST['username'] == ZIPSME_USERNAME) && ($_POST['password'] == ZIPSME_PASSWORD)) || ((isset($_COOKIE['zipsme_admin'])) && ($_COOKIE['zipsme_admin'] == 'logged_in')) ) {
	$logged_in = 'y';
	if ((isset($_GET['logout'])) && ($_GET['logout'] == 'y')) {
		setcookie("zipsme_admin", "", time() - (60*60*24*365), '/');
		$logged_in = 'n';
		$alert = 'You\'ve successfully logged out';
	} else {
		setcookie("zipsme_admin", "logged_in", time() + (60*60*24*365), '/');
	}
	
	if (isset($_POST['url_submitted'])) {
		$url = prepQueryText($_POST['url']);
		$url_name = prepQueryText($_POST['url_name']);
		$url_name = stripLink($url_name);
		$type = $_POST['type'];
		if (linkAvailable($url_name)) {
			insertLink($url_name, $url, $type);
			$alert = 'Link created successfully! <a target="_blank" href="'. $url_name . '">' . SITE_URL . $url_name . '</a> now redirects to ' . $url;		
		} else {
			$alert = 'The link name ' . $url_name . ' is already being used.  Try a different name or edit the existing link';
		}
						  
	}
	
	if (isset($_POST['edit_submitted'])) {
		$url = prepQueryText($_POST['url']);
		$url_name = prepQueryText($_POST['url_name']);
		$type = $_POST['type'];
		updateLink($url_name, $url, $type);
		$alert = 'Update successful!';
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
		$url_name = prepQueryText($_GET['delete']);
		deleteLink($url_name);
		$alert = $url_name . ' has been permanently deleted.';
	}

} else { 
	$logged_in = 'n';
	if (isset($_POST['login_submitted'])) {
		$alert = 'Incorrect username/password combination';
	}
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
<body>
	<center>
    <div id="container">
        <div id="header"><h1><?php echo SITE_NAME; ?> Admin</h1></div>
        <div id="content">
        <?php if (isset($alert)) { ?><p class="alert"><?php echo $alert; ?></p><?php } ?>
            <?php if ($logged_in == 'y') { ?>
            <div id="logout-link"><a href="admin.php">Admin Home</a> | <a href="admin.php?logout=y">Log Out</a></div>
            <?php if (isset($_GET['pre_delete'])) { ?>
            <p class="alert">Are you sure you want to delete the link <strong><?php echo SITE_URL; ?>/<?php echo prepOutputText($_GET['pre_delete']) ?></strong> ?  <a href="admin.php?delete=<?php echo prepOutputText($_GET['pre_delete']) ?>">Yes</a> | <a href="admin.php">No</a></p>
            <?php } ?>	
				<?php if ($view == 'stats') { ?>                                
                <h2>Statistics for <strong><?php echo $summary->url_name; ?></strong></h2>
					<?php if ($summary->total_clicks > 0) { ?>
                        <div id="click-summary" align="left">
                            <h3>Clicks</h3>                    
                            <table cellspacing="0" cellpadding="2" border="0" class="border"><tbody>                	
                                <tr>
                                    <td class="border"><strong>Month</strong></td>
                                    <td class="border"><strong>Clicks</strong></td>
                                </tr>
                                <?php $summary->showClicks(); ?>
                                <tr>
                                    <td class="border">Total:</td>
                                    <td class="border"><?php echo $summary->total_clicks; ?></td>
                                </tr>
                            </tbody></table>
                            <h3>Top Referring Sites</h3>
                            <table cellspacing="0" cellpadding="2" border="0" class="border"><tbody>                	
                                <tr>
                                    <td class="border"><strong>Referrer</strong></td>
                                    <td class="border"><strong>Clicks</strong></td>
                                </tr>
                                <?php $summary->showReferrers(); ?>                    
                                <tr>
                                    <td class="border">Total:</td>
                                    <td class="border"><?php echo $summary->total_clicks; ?></td>
                                </tr>
                            </tbody></table>
                            <h3>Browsers</h3>
                            <table cellspacing="0" cellpadding="2" border="0" class="border"><tbody>                	
                                <tr>
                                    <td class="border"><strong>Browser</strong></td>
                                    <td class="border"><strong>Clicks</strong></td>
                                </tr>
                                <tr>
                                    <td class="border">Internet Explorer</td>
                                    <td class="border"><?php echo $summary->b_ie; ?></td>
                                </tr>						                  
                                <tr>
                                    <td class="border">Mozilla Firefox</td>
                                    <td class="border"><?php echo $summary->b_firefox; ?></td>
                                </tr>						                  
                                <tr>
                                    <td class="border">Webkit (Safari/Chrome)</td>
                                    <td class="border"><?php echo $summary->b_webkit; ?></td>
                                </tr>						                  
                                <tr>
                                    <td class="border">Opera</td>
                                    <td class="border"><?php echo $summary->b_opera; ?></td>
                                </tr>						                  
                                <tr>
                                    <td class="border">Bots/Unknown</td>
                                    <td class="border"><?php echo $summary->b_none; ?></td>
                                </tr>						                  
        
                                <tr>
                                    <td class="border">Total:</td>
                                    <td class="border"><?php echo $summary->total_clicks; ?></td>
                                </tr>
                            </tbody></table>  					                                          
                            <h3>Operating Systems</h3>  
                            <table cellspacing="0" cellpadding="2" border="0" class="border"><tbody>                	
                                <tr>
                                    <td class="border"><strong>Operating System</strong></td>
                                    <td class="border"><strong>Clicks</strong></td>
                                </tr>
                                <tr>
                                    <td class="border">Windows</td>
                                    <td class="border"><?php echo $summary->o_win; ?></td>
                                </tr>						                  
                                <tr>
                                    <td class="border">Mac</td>
                                    <td class="border"><?php echo $summary->o_mac; ?></td>
                                </tr>						                  
                                <tr>
                                    <td class="border">Linux</td>
                                    <td class="border"><?php echo $summary->o_linux; ?></td>
                                </tr>						                  
                                <tr>
                                    <td class="border">Bots/Unknown</td>
                                    <td class="border"><?php echo $summary->o_none; ?></td>
                                </tr>						                  
                                <tr>
                                    <td class="border">Total:</td>
                                    <td class="border"><?php echo $summary->total_clicks; ?></td>
                                </tr>
                            </tbody></table> 
                        </div>                                                     
                    <?php } else { ?>
                        <p>No clicks yet!</p>
                    <?php } ?>
                <?php } else if ($view == 'edit') { ?>
                 <h2>Edit <strong><?php echo $edit->url_name; ?></strong></h2>
                    <form action="admin.php" method="post" id="url-form">
                        <label>Original Link</label><input type="text" name="url" size="50" value="<?php echo $edit->url; ?>" /><br />                        
                        <label>Type</label><select name="type"><option <?php if ($edit->type == '301') { echo 'selected="selected"'; } ?> value="301">301 Permanent Redirect</option><option <?php if ($edit->type == '302') { echo 'selected="selected"'; } ?> value="302">302 Temporary Redirect</option></select><br />
                        <input type="hidden" value="1" name="edit_submitted"/>
                        <input type="hidden" value="<?php echo $edit->url_name; ?>" name="url_name"/>
                        <input type="submit" value="Update" id="form-button"/>
                    </form>
                <?php } else { ?>               	
                <h2>Shorten a New Link</h2>
                <form action="admin.php" method="post" id="url-form">
                    <label>Original Link</label><input type="text" name="url" size="50" /><br />
                    <label>New Link Name</label><input maxlength="255" type="text" name="url_name" value="<?php echo $url_name; ?>" /><br />
                    <label>Type</label><select name="type"><option selected="selected" value="301">301 Permanent Redirect</option><option value="302">302 Temporary Redirect</option></select><br />
                    <input type="hidden" value="1" name="url_submitted"/>
                    <input type="submit" value="Shorten It!" id="form-button"/>
            	</form>
                <h2>Link History</h2>
                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="border"><tbody>                	
                    <tr>
					    <td class="border"><strong>Link Name</strong></td>
                        <td class="border"><strong>Clicks</strong></td>
                        <td class="border"><strong>Options</strong></td>                        
					</tr>
                    <?php showLinkHistory(); ?>
                </tbody></table>
				<?php } ?>
			<?php } else { ?>
            <h2>Login</h2>
            <form action="admin.php" method="post" id="login-form">
            	<label>Username:</label><input type="text" maxlength="100" name="username" /><br />
            	<label>Password:</label><input type="password" maxlength="100" name="password" /><br />
                <input type="hidden" value="1" name="login_submitted"/>
                <input type="submit" value="Log In" id="form-button"/>
            </form>
			<?php } ?>
        </div>
        <div id="footer">Powered by <a href="http://z.ips.me">Z.ips.ME</a></div>
	</div>
    </center>
</body>
</html>

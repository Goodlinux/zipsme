echo  "<?php"                                   > /var/www/zipsme/Const.php
echo "define('DB_USER', '"$DB_USER"');"         >> /var/www/zipsme/Const.php 
echo "define('DB_PASSWORD', '"$DB_PASSWORD"');" >> /var/www/zipsme/Const.php 
echo "define('DB_NAME', '"$DB_NAME"');"         >> /var/www/zipsme/Const.php 
echo "define('DB_SERVER', '"$DB_SERVER"');"     >> /var/www/zipsme/Const.php 
echo "define('SITE_NAME', '"$SITE_NAME"');"     >> /var/www/zipsme/Const.php 
echo "define('SITE_URL', '"$SITE_URL"');"       >> /var/www/zipsme/Const.php 
echo "define('SRV_NAME', '"$SRV_NAME"');"       >> /var/www/zipsme/Const.php 
echo "define('SERVEUR_SSO', '"$SERVEUR_SSO"');" >> /var/www/zipsme/Const.php
echo "define('APPID_SSO', '"$APPID_SSO"');"         >> /var/www/zipsme/Const.php
echo "?>"                                       >> /var/www/zipsme/Const.php 

echo  "<?php"                                   > /var/www/zipsme/Const.php
echo "define('DB_USER', '"$DB_USER"');"         >> /var/www/zipsme/Const.php 
echo "define('DB_PASSWORD', '"$DB_PASSWORD"');" >> /var/www/zipsme/Const.php 
echo "define('DB_NAME', '"$DB_NAME"');"         >> /var/www/zipsme/Const.php 
echo "define('DB_SERVER', '"$DB_SERVER"');"     >> /var/www/zipsme/Const.php 
echo "define('SITE_NAME', '"$SITE_NAME"');"     >> /var/www/zipsme/Const.php 
echo "define('SITE_URL', '"$SITE_URL"');"  uid=root,cn=users,dc=maillet,dc=me     >> /var/www/zipsme/Const.php 
echo "define('LDAP_SRV', '"$LDAP_SRV"');"       >> /var/www/zipsme/Const.php 
echo "define('LDAP_RACINE', 'cn=users,dc="$LDAP_DOMAIN",dc="$LDAP_EXT"');"  >> /var/www/zipsme/Const.php 
echo "define('SRV_NAME', '"$SRV_NAME"');"       >> /var/www/zipsme/Const.php 
echo "?>"                                       >> /var/www/zipsme/Const.php 

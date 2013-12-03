# _ITOP_NAME_ default Apache configuration

Alias /_ITOP_NAME_ _ITOP_DATADIR_/_ITOP_NAME_

<Directory _ITOP_DATADIR_/_ITOP_NAME_>
	Options FollowSymLinks
	DirectoryIndex index.php

	<IfModule mod_php5.c>
		AddType application/x-httpd-php .php

		php_flag magic_quotes_gpc Off
		php_flag track_vars On
		php_flag register_globals Off
	</IfModule>

</Directory>

# Disallow web access to directories that don't need it
<Directory _ITOP_DATADIR_/_ITOP_NAME_/lib>
    Order Deny,Allow
    Deny from All
</Directory>
<Directory _ITOP_DATADIR_/_ITOP_NAME_/conf>
    Order Deny,Allow
    Deny from All
</Directory>
<Directory _ITOP_DATADIR_/_ITOP_NAME_/log>
    Order Deny,Allow
    Deny from All
</Directory>
<Directory _ITOP_DATADIR_/_ITOP_NAME_/data>
    Order Deny,Allow
    Deny from All
</Directory>

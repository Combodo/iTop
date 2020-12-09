<?php

/**
 *
 * Configuration file, generated for the unit tests
 *
 *
 *
 */
$MySettings = array(



	// app_root_url: Root URL used for navigating within the application, or from an email to the application (you can put $SERVER_NAME$ as a placeholder for the server's name)
	//	default: ''
	'app_root_url' => 'http://%server(SERVER_NAME)?:localhost%/itop/iTop/',


);

/**
 *
 * Modules specific settings
 *
 */
$MyModuleSettings = array(
);

/**
 *
 * Data model modules to be loaded. Names are specified as relative paths
 *
 */
$MyModules = array(
	'addons' => array('user rights' => 'addons/userrights/userrightsprofile.class.inc.php'),
);
?>
<?php


// Until we develop a mean to adress this within the setup, let's check that this instance
// of PHP has the php_ldap extension
//
if (function_exists('ldap_connect'))
{

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'authent-ldap/1.0.0',
	array(
		// Identification
		//
		'label' => 'User authentication based on LDAP',
		'category' => 'authentication',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.authent-ldap.php',
		),
		'data.struct' => array(
			//'data.struct.authent-ldap.xml',
		),
		'data.sample' => array(
			//'data.sample.authent-ldap.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
			'host' => 'localhost', // host or IP address of your LDAP server
			'port' => 389,		  // LDAP port (std: 389)
			'default_user' => '', // User and password used for initial "Anonymous" bind to LDAP
			'default_pwd' => '',  // Leave both blank, if anonymous (read-only) bind is allowed
			'base_dn' => 'dc=yourcompany,dc=com', // Base DN for User queries, adjust it to your LDAP schema
			'user_query' => '(&(uid=%1$s)(inetuserstatus=ACTIVE))', // Query used to retrieve each user %1$s => iTop login
																	// For Windows AD use (samaccountname=%1$s) or (userprincipalname=%1$s)
																	
			// Some extra LDAP options, refer to: http://www.php.net/manual/en/function.ldap-set-option.php for more info
			'options' => array(
				LDAP_OPT_PROTOCOL_VERSION => 3,
				LDAP_OPT_REFERRALS => 0,
			),
			'debug' => false,
		),
	)
);

} // if (function_exists('ldap_connect'))

?>

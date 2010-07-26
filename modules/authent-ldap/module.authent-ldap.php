<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'authent-ldap',
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
		'dictionary' => array(
			'en.dict.authent-ldap.php',
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
			'host' => '192.168.10.164',
			'port' => 389,
			'basedn' => 'dc=leconcorde,dc=net',
		),
	)
);

?>

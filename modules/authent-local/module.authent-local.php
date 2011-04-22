<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'authent-local/1.0.0',
	array(
		// Identification
		//
		'label' => 'User authentication based on the local DB',
		'category' => 'authentication',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.authent-local.php',
		),
		'data.struct' => array(
			//'data.struct.authent-local.xml',
		),
		'data.sample' => array(
			//'data.sample.authent-local.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>

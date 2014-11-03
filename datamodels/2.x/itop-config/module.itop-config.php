<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-config/1.0.2',
	array(
		// Identification
		//
		'label' => 'Configuration editor',
		'category' => 'Application management',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => true,
		'visible' => false,

		// Components
		//
		'datamodel' => array(
			'main.itop-config.php',
			//'model.itop-config.php',
		),
		'webservice' => array(
			//'webservices.itop-config.php',
		),
		'dictionary' => array(
			'en.dict.itop-config.php',
			'fr.dict.itop-config.php',
			//'de.dict.itop-config.php',
		),
		'data.struct' => array(
			//'data.struct.itop-config.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-config.xml',
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

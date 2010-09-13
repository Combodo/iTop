<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-request-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'User request management (Service Desk)',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
			'itop-tickets/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-request-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-request-mgmt.php',
			'fr.dict.itop-request-mgmt.php',
			'es_cr.dict.itop-request-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-request-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-request-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '/doc/itop-documentation.htm#RequestMgmt',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>

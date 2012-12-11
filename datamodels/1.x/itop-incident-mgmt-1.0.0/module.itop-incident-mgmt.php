<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-incident-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Incident Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
			'itop-service-mgmt/1.0.0',
			'itop-tickets/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-incident-mgmt.php',
			'main.itop-incident-mgmt.php',
		),
		'data.struct' => array(
			'data.struct.ta-triggers.xml',
			'data.struct.ta-links.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-incident-mgmt.xml',
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

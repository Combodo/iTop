<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-request-mgmt-itil/2.1.0',
	array(
		// Identification
		//
		'label' => 'User request Management ITIL',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.0.0',
			'itop-tickets/2.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-request-mgmt-itil.php',
			'main.itop-request-mgmt-itil.php',
		),
		'data.struct' => array(

		),
		'data.sample' => array(
			//'data.sample.itop-request-mgmt.xml',
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

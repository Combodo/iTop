<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-problem-mgmt/2.1.0',
	array(
		// Identification
		//
		'label' => 'Problem Management',
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
			'model.itop-problem-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-problem-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-problem-mgmt.xml',
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

<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-request-mgmt/2.8.0',
	array(
		// Identification
		//
		'label' => 'Simple Ticket Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-tickets/2.4.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-request-mgmt.php',
			'main.itop-request-mgmt.php',
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

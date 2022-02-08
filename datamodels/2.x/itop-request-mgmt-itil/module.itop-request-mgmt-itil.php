<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-request-mgmt-itil/3.0.1',
	array(
		// Identification
		//
		'label' => 'User request Management ITIL',
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

<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-knownerror-mgmt/3.0.0',
	array(
		// Identification
		//
		'label' => 'Known Errors Database',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.2.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-knownerror-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-knownerror-mgmt.xml',
		),
		'data.sample' => array(
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // No manual installation instructions
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-config/2.7.4',
	array(
		// Identification
		//
		'label' => 'Configuration editor',
		'category' => 'Application management',

		// Setup
		//
		'dependencies' => array(),
		'mandatory' => true,
		'visible' => false,

		// Components
		//
		'datamodel' => array(
			'model.itop-config.php',
			'src/Validator/ConfigNodesVisitor.php',
			'src/Validator/iTopConfigAstValidator.php',
			'src/Validator/iTopConfigSyntaxValidator.php',
),
		'webservice' => array(),
		'dictionary' => array(
			'en.dict.itop-config.php',
			'fr.dict.itop-config.php',
		),
		'data.struct' => array(),
		'data.sample' => array(),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(),
	)
);

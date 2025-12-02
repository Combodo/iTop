<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-config/3.3.0',
	[
		// Identification
		//
		'label' => 'Configuration editor',
		'category' => 'Application management',

		// Setup
		//
		'dependencies' => [],
		'mandatory' => true,
		'visible' => false,

		// Components
		//
		'datamodel' => [
			'src/Validator/ConfigNodesVisitor.php',
			'src/Validator/iTopConfigAstValidator.php',
			'src/Validator/iTopConfigSyntaxValidator.php',
			'src/Validator/iTopConfigValidator.php',
			'src/Controller/ConfigEditorController.php',
],
		'webservice' => [],
		'dictionary' => [
			'en.dict.itop-config.php',
			'fr.dict.itop-config.php',
		],
		'data.struct' => [],
		'data.sample' => [],

		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => [],
	]
);

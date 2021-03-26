<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'module-test-foo/0.0.1',
	array(
		// Identification
		//
		'label' => 'Foo module',
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
			'main.module-test-foo.php',
			'model.module-test-foo.php',
		),
		'webservice' => array(
			//'webservices.module-test-foo.php',
		),
		'dictionary' => array(
			'en.dict.module-test-foo.php',
		),
		'data.struct' => array(
			//'data.struct.module-test-foo.xml',
		),
		'data.sample' => array(
			//'data.sample.module-test-foo.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
			'module-test-foo' => 'bar',
		),
	)
);

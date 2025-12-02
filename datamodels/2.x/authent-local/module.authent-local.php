<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'authent-local/3.2.1',
	[
		// Identification
		//
		'label' => 'User authentication based on the local DB',
		'category' => 'authentication',

		// Setup
		//
		'dependencies' => [
		],
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'model.authent-local.php',
		],
		'data.struct' => [
			//'data.struct.authent-local.xml',
		],
		'data.sample' => [
			//'data.sample.authent-local.xml',
		],

		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//

		'settings' => [
			// see the './datamodel.authent-local.xml' for the default settings!
		],
	]
);

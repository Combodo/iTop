<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'extension-with-execution-policy/0.0.1',
	[
		// Identification
		//
		'label'                => 'Templates foundation',
		'category'             => 'business',

		// Setup
		//
		'dependencies'         => [],
		'mandatory'            => true,
		'visible'              => false,
		'installer'            => 'TemplatesBaseInstaller',

		// Security
		'execution_policy' => [
			'src/Controller/FileInExecutionPolicy.php',
			'src/Controller/CheckAnythingButAdminRequired.php',
		],

		// Components
		//
		'datamodel'            => [
			'model.templates-base.php',
		],
		'webservice'           => [],
		'data.struct'          => [// add your 'structure' definition XML files here,
		],
		'data.sample'          => [// add your sample data XML files here,
		],

		// Documentation
		//
		'doc.manual_setup'     => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings'             => [
			// Select where, in the main UI, the extra data should be displayed:
			//     tab (dedicated tab)
			//     properties (right after the properties, but before the log if any)
			//     none (extra data accessed only by programs)
			'view_extra_data' => 'relations',
		],
	]
);

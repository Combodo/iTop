<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-welcome-itil/3.3.0',
	[
		// Identification
		//
		'label' => 'ITIL skin',
		'category' => 'skin',

		// Setup
		//
		'dependencies' => [
		],
		'mandatory' => true,
		'visible' => false,
		//'installer' => 'MyInstaller',

		// Components
		//
		'datamodel' => [
		],
		'webservice' => [
			//'webservices.itop-welcome-itil.php',
		],
		'data.struct' => [
			//'data.struct.itop-welcome-itil.xml',
		],
		'data.sample' => [
			//'data.sample.itop-welcome-itil.xml',
		],

		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => [
			//'some_setting' => 'some value',
		],
	]
);

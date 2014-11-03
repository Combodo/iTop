<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-welcome-itil/2.1.0',
	array(
		// Identification
		//
		'label' => 'ITIL skin',
		'category' => 'skin',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => true,
		'visible' => false,
		//'installer' => 'MyInstaller',

		// Components
		//
		'datamodel' => array(
			'main.itop-welcome-itil.php',
			'model.itop-welcome-itil.php',
		),
		'webservice' => array(
			//'webservices.itop-welcome-itil.php',
		),
		'data.struct' => array(
			//'data.struct.itop-welcome-itil.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-welcome-itil.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
			//'some_setting' => 'some value',
		),
	)
);

?>

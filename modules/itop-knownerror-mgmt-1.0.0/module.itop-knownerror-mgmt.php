<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-knownerror-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Known Errors Database',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
			'itop-tickets/1.0.0',
			'itop-incident-mgmt/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-knownerror-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-knownerror-mgmt.php',
			'es_cr.dict.itop-knownerror-mgmt.php',
			'fr.dict.itop-knownerror-mgmt.php',
			'de.dict.itop-knownerror-mgmt.php',
			'pt_br.dict.itop-knownerror-mgmt.php',
			'ru.dict.itop-knownerror-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-knownerror-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-knownerror-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // No manual installation instructions
		'doc.more_information' => '/doc/itop-documentation.htm#KnownErrorsDB',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>

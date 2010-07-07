<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-incident-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Incident Management',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
			'itop-service-mgmt/1.0.0',
			'itop-tickets/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-incident-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-incident-mgmt.php',
			'fr.dict.itop-incident-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-incident-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-incident-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '/doc/itop-documentation.htm#IncidentMgmt',
	)
);

?>

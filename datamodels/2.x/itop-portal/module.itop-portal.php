<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-portal/1.0.0', array(
	// Identification
	'label' => 'Enhanced Customer Portal',
	'category' => 'Portal',
	// Setup
	'dependencies' => array(
		'itop-portal-base/1.0.0'
	),
	'mandatory' => false,
	'visible' => true,
	// Components
	'datamodel' => array(
		'main.itop-portal.php'
	),
	'webservice' => array(
	//'webservices.itop-portal.php',
	),
	'dictionary' => array(
	),
	'data.struct' => array(
	//'data.struct.itop-portal.xml',
	),
	'data.sample' => array(
	//'data.sample.itop-portal.xml',
	),
	// Documentation
	'doc.manual_setup' => '',
	'doc.more_information' => '',
	// Default settings
	'settings' => array(
	),
	)
);
?>

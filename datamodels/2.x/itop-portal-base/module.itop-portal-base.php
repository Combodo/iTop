<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-portal-base/2.5.0', array(
	// Identification
	'label' => 'Portal Development Library',
		'category' => 'Portal',
	// Setup
	'dependencies' => array(
	),
	'mandatory' => false,
	'visible' => true,
	// Components
	'datamodel' => array(
		'portal/src/controllers/abstractcontroller.class.inc.php',
		'portal/src/controllers/brickcontroller.class.inc.php',
		'portal/src/entities/abstractbrick.class.inc.php',
		'portal/src/entities/portalbrick.class.inc.php',
		'portal/src/routers/abstractrouter.class.inc.php',
	),
	'webservice' => array(
	//'webservices.itop-portal-base.php',
	),
	'dictionary' => array(
		'fr.dict.itop-portal-base.php',
	//'de.dict.itop-portal-base.php',
	),
	'data.struct' => array(
	//'data.struct.itop-portal-base.xml',
	),
	'data.sample' => array(
	//'data.sample.itop-portal-base.xml',
	),
	// Documentation
	'doc.manual_setup' => '',
	'doc.more_information' => '',
	// Default settings
	'settings' => array(
	),
	)
);

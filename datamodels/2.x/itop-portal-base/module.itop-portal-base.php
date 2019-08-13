<?php

/** @noinspection PhpUnhandledExceptionInspection */
SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-portal-base/2.7.0', array(
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
		// Note: The autoloader is there instead of portal/config/bootstrap.php in order to be available for other modules with a dependency on this one.
		// eg. If a module has a class extending \Combodo\iTop\Portal\Controller\AbstractController, it needs to find it even if the portal kernel is not loaded.
		'portal/vendor/autoload.php',
		'model.itop-portal-base.php',
	),
	'webservice' => array(
	//'webservices.itop-portal-base.php',
	),
	'dictionary' => array(
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

<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-config-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Configuration Management (CMDB)',

		// Setup
		//
		'dependencies' => array(
			//'itop-config-mgmt/1.0.0',
		),
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-config-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-config-mgmt.php',
			'fr.dict.itop-config-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-config-mgmt.xml',
		),
		'data.sample' => array(
			'data.sample.organization.xml',
			'data.sample.location.xml',
			'data.sample.team.xml',
			'data.sample.contact.xml',
			'data.sample.server.xml',
			'data.sample.application.xml',
			'data.sample.business.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // No manual installation required
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

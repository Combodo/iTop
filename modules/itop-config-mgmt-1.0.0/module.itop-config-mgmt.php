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
		),
		'data.struct' => array(
			//'data.struct.itop-config-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-config-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/xxx/yyy.htm',
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

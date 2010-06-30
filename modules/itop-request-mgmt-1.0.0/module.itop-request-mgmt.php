<?php


SetupWebPage::AddModule(
	'itop-request-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'User request management (Service Desk)',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-request-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-request-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-request-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-request-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/xxx/yyy.htm',
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

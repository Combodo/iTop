<?php


SetupWebPage::AddModule(
	'itop-service-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Service Management (services, SLAs, contracts)',

		// Setup
		//
		'dependencies' => array(
			//'itop-service-mgmt/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-service-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-service-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-service-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-service-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/xxx/yyy.htm',
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

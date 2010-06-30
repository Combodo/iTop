<?php


SetupWebPage::AddModule(
	'itop-change-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Change Management',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
			'itop-ticket/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-change-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-change-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-change-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-change-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/xxx/yyy.htm',
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

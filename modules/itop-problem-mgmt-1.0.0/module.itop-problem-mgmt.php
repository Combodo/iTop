<?php


SetupWebPage::AddModule(
	'itop-problem-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Problem Managemen',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
			'itop-tickets/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-problem-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-problem-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-problem-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-problem-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/xxx/yyy.htm',
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

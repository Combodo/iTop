<?php


SetupWebPage::AddModule(
	'itop-incident-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Incident Management',

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
			'model.itop-incident-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-incident-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-incident-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-incident-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/xxx/yyy.htm',
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

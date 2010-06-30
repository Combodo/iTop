<?php


SetupWebPage::AddModule(
	'itop-basic/1.0.0',
	array(
		// Identification
		//
		'label' => 'Change Management',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-basic.php',
		),
		'dictionary' => array(
			'en.dict.itop-basic.php',
		),
		'data.struct' => array(
			//'data.struct.itop-basic.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-basic.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/xxx/yyy.htm',
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

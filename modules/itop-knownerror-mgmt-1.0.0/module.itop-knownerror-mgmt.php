<?php


SetupWebPage::AddModule(
	'itop-knownerror-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Known Errors Database',

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
			'model.itop-knownerror-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-knownerror-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-knownerror-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-knownerror-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/xxx/yyy.htm',
		'doc.more_information' => '/doc/xxx/yyy.htm',
	)
);

?>

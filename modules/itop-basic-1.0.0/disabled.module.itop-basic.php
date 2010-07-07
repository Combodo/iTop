<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-basic/1.0.0',
	array(
		// Identification
		//
		'label' => 'iTop Basic Model',

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

<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-hub-connector/3.2.1',
	[
		// Identification
		//
		'label' => 'iTop Hub Connector',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-config-mgmt/2.4.0', // Actually this module requires iTop 2.4.1 minimum
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'menus.php',
			'hubnewsroomprovider.class.inc.php',
		],
		'webservice' => [

		],
		'data.struct' => [
			// add your 'structure' definition XML files here,
		],
		'data.sample' => [
			// add your sample data XML files here,
		],

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any
	]
);

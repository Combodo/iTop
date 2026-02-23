<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-container-mgmt/3.3.0',
	[
		// Identification
		//
		'label' => 'Container management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-virtualization-mgmt/3.3.0',
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [

		],
		'webservice' => [

		],
		'data.struct' => [
			// add your 'structure' definition XML files here,
			'data/en_us.data.itop-container-type.xml',
			'data/en_us.data.itop-container-image-type.xml',
		],
		'data.sample' => [
			// add your sample data XML files here,
		],

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => [
			// Module specific settings go here, if any
		],
	]
);

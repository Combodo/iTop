<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-flow-map/3.3.0',
	[
		// Identification
		//
		'label' => 'Applications data flows',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-config-mgmt/3.2.0',
			'itop-structure/3.2.0||itop-virtualization/3.2.0||itop-container-mgmt/3.2.0',
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
			'data/en_us.data.itop-flow-map.xml',
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

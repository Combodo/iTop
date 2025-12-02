<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-request-mgmt/3.2.1',
	[
		// Identification
		//
		'label' => 'Simple Ticket Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-tickets/2.4.0',
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'main.itop-request-mgmt.php',
		],
		'data.struct' => [

		],
		'data.sample' => [
			//'data.sample.itop-request-mgmt.xml',
		],

		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => [
		],
	]
);

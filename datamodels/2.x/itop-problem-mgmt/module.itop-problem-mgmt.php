<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-problem-mgmt/3.2.1',
	[
		// Identification
		//
		'label' => 'Problem Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-tickets/2.0.0',
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [
		],
		'data.struct' => [
			//'data.struct.itop-problem-mgmt.xml',
		],
		'data.sample' => [
			//'data.sample.itop-problem-mgmt.xml',
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

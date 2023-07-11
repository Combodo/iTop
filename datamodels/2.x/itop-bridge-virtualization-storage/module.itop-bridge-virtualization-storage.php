<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-bridge-virtualization-storage/3.2.0',
	array(
		// Identification
		//
		'label' => 'Links between virtualization and storage',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-storage-mgmt/2.2.0',
			'itop-virtualization-mgmt/2.2.0',
		),
		'mandatory' => false,
		'visible' => false,
		'auto_select' => 'SetupInfo::ModuleIsSelected("itop-storage-mgmt") && SetupInfo::ModuleIsSelected("itop-virtualization-mgmt")',

		// Components
		//
		'datamodel' => array(
		),
		'data.struct' => array(
			//'data.struct.itop-change-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-change-mgmt.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

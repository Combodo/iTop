<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-bridge-cmdb-ticket/3.3.0',
	[
		// Identification
		//
		'label' => 'Bridge for CMDB and Ticket',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
				'itop-config-mgmt/2.7.1',
				'itop-tickets/2.7.0',
		],
		'mandatory' => false,
		'visible' => false,
		'auto_select' => 'SetupInfo::ModuleIsSelected("itop-config-mgmt") && SetupInfo::ModuleIsSelected("itop-tickets") ',

		// Components
		//
		'datamodel' => [
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

		// Default settings
		//
		'settings' => [
			// Module specific settings go here, if any
		],
	]
);

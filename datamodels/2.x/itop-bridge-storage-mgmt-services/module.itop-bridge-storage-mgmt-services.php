<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-bridge-storage-mgmt-services/3.2.1',
	[
		// Identification
		//
		'label' => 'Bridge for CMDB Virtualization objects and Services',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-config-mgmt/2.7.1',
			'itop-service-mgmt/2.7.1 || itop-service-mgmt-provider/2.7.1',
			'itop-storage-mgmt/3.1.0',
		],
		'mandatory' => false,
		'visible' => false,
		'auto_select' => 'SetupInfo::ModuleIsSelected("itop-storage-mgmt") &&  (SetupInfo::ModuleIsSelected("itop-service-mgmt") || SetupInfo::ModuleIsSelected("itop-service-mgmt-provider")) ',

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

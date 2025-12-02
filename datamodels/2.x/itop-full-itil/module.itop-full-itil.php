<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-full-itil/3.2.1',
	[
		// Identification
		//
		'label' => 'Bridge - Request management ITIL + Incident management ITIL',
		'category' => 'business',
		// Setup
		//
		'dependencies' => [
			'itop-request-mgmt-itil/2.3.0',
			'itop-incident-mgmt-itil/2.3.0',
		],
		'mandatory' => false,
		'visible' => false,
		'auto_select' => 'SetupInfo::ModuleIsSelected("itop-request-mgmt-itil") && SetupInfo::ModuleIsSelected("itop-incident-mgmt-itil")',
		// Components
		//
		'datamodel' => [],
		'webservice' => [],
		'data.struct' => [// add your 'structure' definition XML files here,
		],
		'data.sample' => [// add your sample data XML files here,
		],
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any
		// Default settings
		//
		'settings' => [// Module specific settings go here, if any
		],
	]
);

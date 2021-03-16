<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-full-itil/2.7.4',
	array(
		// Identification
		//
		'label' => 'Bridge - Request management ITIL + Incident management ITIL',
		'category' => 'business',
		// Setup
		//
		'dependencies' => array(
			'itop-request-mgmt-itil/2.3.0',
			'itop-incident-mgmt-itil/2.3.0',
		),
		'mandatory' => false,
		'visible' => false,
		'auto_select' => 'SetupInfo::ModuleIsSelected("itop-request-mgmt-itil") && SetupInfo::ModuleIsSelected("itop-incident-mgmt-itil")',
		// Components
		//
		'datamodel' => array(//'model.itop-portal-full-itil.php'
		),
		'webservice' => array(),
		'data.struct' => array(// add your 'structure' definition XML files here,
		),
		'data.sample' => array(// add your sample data XML files here,
		),
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any
		// Default settings
		//
		'settings' => array(// Module specific settings go here, if any
		),
	)
);

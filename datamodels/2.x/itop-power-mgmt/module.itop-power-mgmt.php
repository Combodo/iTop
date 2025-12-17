<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-power-mgmt/3.3.0',
	array(
		// Identification
		//
		'label' => 'Extended Power Management for iTop Datacenter Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-datacenter-mgmt/3.3.0'
		),
		'mandatory' => false,
		'visible' => true, // To prevent auto-install but shall not be listed in the install wizard
		'auto_select' => 'SetupInfo::ModuleIsSelected("itop-datacenter-mgmt")',

		// Components
		//
		'datamodel' => array(
			'model.itop-power-mgmt.php'
		),
		'webservice' => array(
			
		),
		'data.struct' => array(
			// add your 'structure' definition XML files here,
		),
		'data.sample' => array(
			// add your sample data XML files here,
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any 

		// Default settings
		//
		'settings' => array(
			// Module specific settings go here, if any
		),
	)
);


?>

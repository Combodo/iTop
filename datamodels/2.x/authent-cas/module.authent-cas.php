<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'authent-cas/3.2.0',
	array(
		// Identification
		//
		'label' => 'CAS SSO',
		'category' => 'authentication',

		// Setup
		//
		'dependencies' => array(
			
		),
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'vendor/autoload.php',
			'src/CASLoginExtension.php',
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
			// Authentication
			'cas_debug' => false,
			'cas_host' => '',
			'cas_port' => '',
			'cas_context' => '',
			'cas_version' => '',
			'service_base_url' => '',
		),
	)
);

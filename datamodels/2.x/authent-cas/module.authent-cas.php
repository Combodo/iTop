<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'authent-cas/3.3.0',
	[
		// Identification
		//
		'label' => 'CAS SSO',
		'category' => 'authentication',

		// Setup
		//
		'dependencies' => [

		],
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'vendor/autoload.php',
			'src/CASLoginExtension.php',
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
			// Authentication
			'cas_debug' => false,
			'cas_host' => '',
			'cas_port' => '',
			'cas_context' => '',
			'cas_version' => '',
			'service_base_url' => '',
		],
	]
);

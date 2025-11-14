<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-oauth-client/3.3.0',
	[
		// Identification
		//
		'label'        => 'OAuth 2.0 client',
		'category'     => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-welcome-itil/3.1.0,',
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'vendor/autoload.php',
			'src/Service/PopupMenuExtension.php',
			'src/Service/ApplicationUIExtension.php',
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

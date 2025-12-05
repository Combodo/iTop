<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'combodo-backoffice-fullmoon-tritanopia-theme/3.3.0',
	[
		// Identification
		//
		'label' => 'Backoffice: Fullmoon with tritanopia accessibility theme',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [

		],
		'mandatory' => true,
		'visible' => false,

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

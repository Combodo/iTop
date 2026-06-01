<?php

/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'finalclass_ext2_module1/6.6.6',
	[
		// Identification
		//
		'label'        => 'Ext For Test',
		'category'     => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-structure/3.2.0',
		],
		'mandatory' => false,
		'visible' => true,
		'installer' => '',

		// Components
		//
		'datamodel' => [
			'model.finalclass_ext2_module1.php',
		],
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

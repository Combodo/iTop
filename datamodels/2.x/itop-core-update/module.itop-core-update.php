<?php
/**
 *  @copyright   Copyright (C) 2010-2019 Combodo SARL
 *  @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-core-update/2.7.0',
	array(
		// Identification
		//
		'label' => 'iTop Core Update',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-twig-base/1.0.0',
            'itop-files-information/1.0.0',
            'combodo-db-tools/1.0.8',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-core-update.php',
			'src/Service/RunTimeEnvironmentCoreUpdater.php',
			'src/Service/CoreUpdater.php',
			'src/Controller/UpdateController.php',
			'src/Controller/AjaxController.php',
		),
		'webservice' => array(),
		'data.struct' => array(),
		'data.sample' => array(),

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => array(),
	)
);

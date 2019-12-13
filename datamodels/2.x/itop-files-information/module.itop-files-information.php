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
	'itop-files-information/1.0.0',
	array(
		// Identification
		//
		'label' => 'iTop files information',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => false,
		'visible' => false,

		// Components
		//
		'datamodel' => array(
		    'model.itop-files-information.php',
            'src/Service/FilesInformation.php',
            'src/Service/FilesInformationException.php',
			'src/Service/FilesInformationUtils.php',
			'src/Service/FilesIntegrity.php',
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

<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-twig-base/1.0.0',
	array(
		// Identification
		//
		'label' => 'iTop Twig Base',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(),
		'mandatory' => false,
		'visible' => false,

		// Components
		//
		'datamodel' => array(
			'model.itop-twig-base.php',
			'src/Controller/Controller.php',
			'src/Twig/Extension.php',
			'src/Twig/TwigHelper.php',
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

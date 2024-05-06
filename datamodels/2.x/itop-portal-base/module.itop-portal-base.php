<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

/** @noinspection PhpUnhandledExceptionInspection */
SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-portal-base/3.2.0', array(
	// Identification
	'label' => 'Portal Development Library',
		'category' => 'Portal',
	// Setup
	'dependencies' => array(
	),
	'mandatory' => true,
	'visible' => false,
	// Components
	'datamodel' => array(
		// Note: The autoloader is there instead of portal/config/bootstrap.php in order to be available for other modules with a dependency on this one.
		// eg. If a module has a class extending \Combodo\iTop\Portal\Controller\AbstractController, it needs to find it even if the portal kernel is not loaded.
		'portal/vendor/autoload.php',
	),
	'webservice' => array(
	//'webservices.itop-portal-base.php',
	),
	'dictionary' => array(
	),
	'data.struct' => array(
	//'data.struct.itop-portal-base.xml',
	),
	'data.sample' => array(
	//'data.sample.itop-portal-base.xml',
	),
	// Documentation
	'doc.manual_setup' => '',
	'doc.more_information' => '',
	// Default settings
	'settings' => array(
	),
	)
);

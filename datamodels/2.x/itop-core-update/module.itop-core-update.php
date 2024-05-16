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

//
// iTop module definition file
//

/** @noinspection PhpUnhandledExceptionInspection */
SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-core-update/3.2.0',
	[
		// Identification
		//
		'label' => 'iTop Core Update',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
            'itop-files-information/2.7.0',
            'combodo-db-tools/2.7.0',
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'src/Service/RunTimeEnvironmentCoreUpdater.php',
			'src/Service/CoreUpdater.php',
			'src/Controller/UpdateController.php',
			'src/Controller/AjaxController.php',
		],
		'webservice' => [],
		'data.struct' => [],
		'data.sample' => [],

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => [],
	]
);

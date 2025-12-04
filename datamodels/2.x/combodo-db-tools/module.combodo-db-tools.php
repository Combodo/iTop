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
	'combodo-db-tools/3.3.0',
	[
		// Identification
		//
		'label' => 'Database maintenance tools',
		'category' => 'Application management',

		// Setup
		//
		'dependencies' => [
			'itop-structure/3.0.0',
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'src/Service/DBToolsUtils.php',
			'src/Service/DBAnalyzerUtils.php',
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

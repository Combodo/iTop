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
	'itop-portal/3.2.1',
	[
	// Identification
	'label' => 'Enhanced Customer Portal',
	'category' => 'Portal',
	// Setup
	'dependencies' => [
		'itop-portal-base/2.7.0',
	],
	'mandatory' => false,
	'visible' => true,
	// Components
	'datamodel' => [
		'main.itop-portal.php',
	],
	'webservice' => [
	//'webservices.itop-portal.php',
	],

	'data.struct' => [
	//'data.struct.itop-portal.xml',
	],
	'data.sample' => [
	//'data.sample.itop-portal.xml',
	],
	// Documentation
	'doc.manual_setup' => '',
	'doc.more_information' => '',
	// Default settings
	'settings' => [
	],
	]
);

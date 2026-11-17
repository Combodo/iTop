<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
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

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-portal-dark-theme/3.4.0',
	[
		// Identification
		'label' => 'Portal: dark theme',
		'category' => 'Portal',

		// Setup
		'dependencies' => [
			'itop-portal-base/3.4.0',
		],
		'mandatory' => true,
		'visible' => false,

		// Components
		'datamodel' => [
			'main.itop-portal-dark-theme.php',
		],
		'webservice' => [],
		'data.struct' => [],
		'data.sample' => [],

		// Documentation
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		'settings' => [
			// Back-office themes considered as dark: users having one of them always get the dark portal
			'dark_themes' => ['darkmoon'],
			// For the other users, follow the browser / OS color scheme (prefers-color-scheme). If false, they get the light portal.
			'follow_os' => true,
		],
	]
);

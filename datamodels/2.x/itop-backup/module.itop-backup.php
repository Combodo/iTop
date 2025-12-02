<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-backup/3.3.0',
	[
		// Identification
		//
		'label' => 'Backup utilities',
		'category' => 'Application management',

		// Setup
		//
		'dependencies' => [
		],
		'mandatory' => true,
		'visible' => false,

		// Components
		//
		'datamodel' => [
			'main.itop-backup.php',
		],
		'webservice' => [
			//'webservices.itop-backup.php',
		],
		'dictionary' => [
			'en.dict.itop-backup.php',
			'fr.dict.itop-backup.php',
			//'de.dict.itop-backup.php',
		],
		'data.struct' => [
			//'data.struct.itop-backup.xml',
		],
		'data.sample' => [
			//'data.sample.itop-backup.xml',
		],

		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => [
			'mysql_bindir' => '',
			'week_days' => 'monday, tuesday, wednesday, thursday, friday',
			'time' => '23:30',
			//'file_name_format' => '__DB__-%Y-%m-%d_%H_%M',
			'retention_count' => 5,
			'enabled' => true,
			'itop_backup_incident' => '',
		],
	]
);

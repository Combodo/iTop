<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-backup/2.1.1',
	array(
		// Identification
		//
		'label' => 'Backup utilities',
		'category' => 'Application management',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => true,
		'visible' => false,

		// Components
		//
		'datamodel' => array(
			'main.itop-backup.php',
			//'model.itop-backup.php',
		),
		'webservice' => array(
			//'webservices.itop-backup.php',
		),
		'dictionary' => array(
			'en.dict.itop-backup.php',
			'fr.dict.itop-backup.php',
			//'de.dict.itop-backup.php',
		),
		'data.struct' => array(
			//'data.struct.itop-backup.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-backup.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
			'mysql_bindir' => '',
			'week_days' => 'monday, tuesday, wednesday, thursday, friday',
			'time' => '23:30',
			//'file_name_format' => '__DB__-%Y-%m-%d_%H_%M',
			'retention_count' => 5, 
			'enabled' => true,
			'debug' => false
		),
	)
);

?>

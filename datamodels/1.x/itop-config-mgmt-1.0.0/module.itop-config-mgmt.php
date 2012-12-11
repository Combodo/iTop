<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-config-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Configuration Management (CMDB)',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			//'itop-config-mgmt/1.0.0',
		),
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'main.itop-config-mgmt.php',
			'model.itop-config-mgmt.php',
		),
		'data.struct' => array(
			'data.struct.Audit.xml',
		),
		'data.sample' => array(
			'data.sample.Organization.xml',
			'data.sample.Location.xml',
			'data.sample.Software.xml',
			'data.sample.Contact.xml',
			'data.sample.lnkTeamToContact.xml',
			'data.sample.FunctionalCI.xml',
			'data.sample.DBServerInstance.xml',
			'data.sample.ApplicationInstance.xml',
			'data.sample.DatabaseInstance.xml',
			'data.sample.NetworkInterface.xml',
			'data.sample.lnkCIToContact.xml',
			'data.sample.lnkProcessToSolution.xml',
			'data.sample.lnkSolutionToCI.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // Some manual installation required
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>

<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-service-mgmt/1.0.0',
	array(
		// Identification
		//
		'label' => 'Service Management (services, SLAs, contracts)',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
		),
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-service-mgmt.php',
			'main.itop-service-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-service-mgmt.xml',
		),
		'data.sample' => array(
			'data.sample.Service.xml',
			'data.sample.ServiceSubcategory.xml',
			'data.sample.SLA.xml',
			'data.sample.SLT.xml',
			'data.sample.lnkSLTToSLA.xml',
			'data.sample.Contract.xml',
			'data.sample.lnkContractToSLA.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // No manual installation instructions
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>

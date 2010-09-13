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
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-service-mgmt.php',
		),
		'dictionary' => array(
			'en.dict.itop-service-mgmt.php',
			'fr.dict.itop-service-mgmt.php',
			'es_cr.dict.itop-service-mgmt.php',
			'de.dict.itop-service-mgmt.php',
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
		'doc.more_information' => '/doc/itop-documentation.htm#ServiceMgmt',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>

<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-service-mgmt-provider/2.0.0',
	array(
		// Identification
		//
		'label' => 'Service Management (services, SLAs, contracts) for Service providers',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.0.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-service-mgmt-provider.php',
		),
		'data.struct' => array(
			//'data.struct.itop-service-mgmt.xml',
		),
		'data.sample' => array(
			'data.sample.contracts.xml',
			'data.sample.services.xml',
			'data.sample.serviceelements.xml',
			'data.sample.sla.xml',
			'data.sample.slt.xml',
			'data.sample.sltsla.xml',
	//		'data.sample.coveragewindows.xml',
			'data.sample.contractservice.xml',
			'data.sample.deliverymodel.xml',
			'data.sample.deliverymodelcontact.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // No manual installation instructions
		'doc.more_information' => '/doc/itop-documentation.htm#ServiceMgmtProvider',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>

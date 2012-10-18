<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-config-mgmt/2.0.0',
	array(
		// Identification
		//
		'label' => 'Configuration Management (CMDB)',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-config-mgmt.php',
			'main.itop-config-mgmt.php',
		),
		'data.struct' => array(
		),
		'data.sample' => array(
			'data.sample.organizations.xml',
			'data.sample.brand.xml',
			'data.sample.model.xml',
			'data.sample.osfamily.xml',
			'data.sample.osversion.xml',
			'data.sample.networkdevicetype.xml',
			'data.sample.contacttype.xml',
			'data.sample.locations.xml',
			'data.sample.persons.xml',
			'data.sample.teams.xml',
			'data.sample.contactteam.xml',
			'data.sample.racks.xml',
			'data.sample.servers.xml',
			'data.sample.nw-devices.xml',
			'data.sample.farm.xml',
			'data.sample.hypervisor.xml',
			'data.sample.vm.xml',
			'data.sample.software.xml',
			'data.sample.dbserver.xml',
			'data.sample.dbschema.xml',
			'data.sample.webserver.xml',
			'data.sample.webapp.xml',
			'data.sample.applications.xml',
			'data.sample.applicationsolutionci.xml',

		),
		
		// Documentation
		//
		'doc.manual_setup' => '/doc/itop-documentation.htm#Installation', // Some manual installation required
		'doc.more_information' => '/doc/itop-documentation.htm#ConfigMgmt',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>

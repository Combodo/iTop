<?php

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-service-mgmt/3.3.0',
	[
		// Identification
		//
		'label' => 'Service Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-tickets/2.0.0',
		],
		'mandatory' => false,
		'visible' => true,
		'installer' => 'ServiceMgmtInstaller',

		// Components
		//
		'datamodel' => [
		],
		'data.struct' => [
			'data/data.itop-contracttype.en_us.xml',
		],
		'data.sample' => [
	//		'data/data.sample.contracts.xml',   =>  replaced by localized data
			'data/data.sample.customercontracts.en_us.xml',
			'data/data.sample.providercontracts.xml',
	//		'data/data.sample.servicefamilies.xml', =>  replaced by localized data
			'data/data.sample.servicefamilies.en_us.xml',
	//		'data/data.sample.services.xml',  =>  replaced by localized data
			'data/data.sample.services.en_us.xml',
	//		'data/data.sample.serviceelements.xml',  =>  replaced by localized data
			'data/data.sample.serviceelements.en_us.xml',
			'data/data.sample.contactservice.xml',
	//		'data/data.sample.sla.xml',  =>  replaced by localized data
			'data/data.sample.sla.en_us.xml',
	//		'data/data.sample.slt.xml',  =>  replaced by localized data
			'data/data.sample.slt.en_us.xml',
			'data/data.sample.sltsla.xml',
	//		'data/data.sample.contractservice.xml',  =>  replaced by customercontractservice and providercontractservice files
			'data/data.sample.customercontractservice.xml',
			'data/data.sample.providercontractservice.xml',
			'data/data.sample.deliverymodel.xml',
			'data/data.sample.deliverymodelcontact.xml',
			'data/data.sample.organizations.xml',
		],

		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => [
		],
	]
);

if (!class_exists('ServiceMgmtInstaller')) {
	// Module installation handler
	//
	class ServiceMgmtInstaller extends ModuleInstallerAPI
	{
		public static function BeforeWritingConfig(Config $oConfiguration)
		{
			// If you want to override/force some configuration values, do it here
			return $oConfiguration;
		}

		/**
		 * Handler called before creating or upgrading the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function BeforeDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			if (strlen($sPreviousVersion) > 0) {
				self::RenameEnumValueInDB('SLT', 'request_type', 'servicerequest', 'service_request');
			}
		}

		/**
		 * Handler called after the creation/update of the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
		}
	}
}

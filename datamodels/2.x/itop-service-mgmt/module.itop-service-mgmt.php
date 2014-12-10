<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-service-mgmt/2.1.0',
	array(
		// Identification
		//
		'label' => 'Service Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.0.0',
			'itop-tickets/2.0.0',
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'ServiceMgmtInstaller',

		// Components
		//
		'datamodel' => array(
			'model.itop-service-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-service-mgmt.xml',
		),
		'data.sample' => array(
			'data.sample.organizations.xml',
			'data.sample.contracts.xml',
			'data.sample.services.xml',
			'data.sample.serviceelements.xml',
			'data.sample.sla.xml',
			'data.sample.slt.xml',
			'data.sample.sltsla.xml',
	//		'data.sample.coveragewindows.xml',
			'data.sample.contractservice.xml',
	//		'data.sample.deliverymodel.xml',
			'data.sample.deliverymodelcontact.xml',
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

if (!class_exists('ServiceMgmtInstaller'))
{
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
			if (strlen($sPreviousVersion) > 0)
			{
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
?>

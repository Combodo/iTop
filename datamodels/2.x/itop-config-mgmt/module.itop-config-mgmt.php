<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-config-mgmt/2.1.0',
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
		'installer' => 'ConfigMgmtInstaller',

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
			'data.sample.servers.xml',
			'data.sample.nw-devices.xml',
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
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

if (!class_exists('ConfigMgmtInstaller'))
{
	// Module installation handler
	//
	class ConfigMgmtInstaller extends ModuleInstallerAPI
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
				// If you want to migrate data from one format to another, do it here
				self::RenameEnumValueInDB('Software', 'type', 'DBserver', 'DBServer');
				self::RenameEnumValueInDB('Software', 'type', 'Webserver', 'WebServer');
				self::RenameEnumValueInDB('Model', 'type', 'SANswitch', 'SANSwitch');
				self::RenameEnumValueInDB('Model', 'type', 'IpPhone', 'IPPhone');
				self::RenameEnumValueInDB('Model', 'type', 'Telephone', 'Phone');
				self::RenameClassInDB('DBserver', 'DBServer');
				self::RenameClassInDB('OSfamily', 'OSFamily');
				self::RenameClassInDB('OSversion', 'OSVersion');
				self::RenameClassInDB('Webserver', 'WebServer');
				self::RenameClassInDB('OSpatch', 'OSPatch');
				self::RenameClassInDB('lnkFunctionalCIToOSpatch', 'lnkFunctionalCIToOSPatch');
				self::RenameClassInDB('OsLicence', 'OSLicence');
				self::RenameClassInDB('IOSversion', 'IOSVersion');
				self::RenameClassInDB('IPinterface', 'IPInterface');
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
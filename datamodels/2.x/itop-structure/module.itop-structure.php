<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-structure/2.8.0',
	array(
		// Identification
		//
		'label' => 'Structure de base iTop',
		'category' => 'core',

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
			'main.itop-structure.php',
			'model.itop-structure.php',
		),
		'data.struct' => array(
		),
		'data.sample' => array(
			'data.sample.organizations.xml',
			'data.sample.locations.xml',
			'data.sample.persons.xml',
			'data.sample.teams.xml',
			'data.sample.contactteam.xml',
			'data.sample.contacttype.xml',
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

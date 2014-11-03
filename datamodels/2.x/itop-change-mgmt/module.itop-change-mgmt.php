<?php


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-change-mgmt/2.1.0',
	array(
		// Identification
		//
		'label' => 'Change Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.0.0',
			'itop-tickets/2.0.0',
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'ChangeManagementInstaller',

		// Components
		//
		'datamodel' => array(
			'model.itop-change-mgmt.php',
		),
		'data.struct' => array(
			//'data.struct.itop-change-mgmt.xml',
		),
		'data.sample' => array(
			//'data.sample.itop-change-mgmt.xml',
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

// Module installation handler
//
class ChangeManagementInstaller extends ModuleInstallerAPI
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
		// If you want to migrate data from one format to another, do it here
	}
	
	/**
	 * Handler called after the creation/update of the database schema
	 * @param $oConfiguration Config The new configuration of the application
	 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
	 * @param $sCurrentVersion string Current version number of the module
	 */
	public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
	{
		// Bug #464 - start_date was both in Ticket and Change tables
		//
		$sSourceTable = 'change';
		$sSourceKeyField = 'id';

		$sTargetTable = 'ticket';
		$sTargetKeyField = 'id';

		$sField = 'start_date';

		if (CMDBSource::IsField($sSourceTable, $sField) && CMDBSource::IsField($sTargetTable, $sField) && CMDBSource::IsField($sSourceTable, $sSourceKeyField) && CMDBSource::IsField($sTargetTable, $sTargetKeyField))
		{
			SetupWebPage::log_info("Issue #464 - Copying change/start_date into ticket/start_date"); 
			$sRepair = "UPDATE `$sTargetTable`, `$sSourceTable` SET `$sTargetTable`.`$sField` = `$sSourceTable`.`$sField` WHERE `$sTargetTable`.`$sField` IS NULL AND`$sTargetTable`.`$sTargetKeyField` = `$sSourceTable`.`$sSourceKeyField`";
			CMDBSource::Query($sRepair);
		}
	}
}

?>

<?php

/**
 * Module itop-global-requests
 *
 * @copyright   Copyright (C) 2012-2019 Combodo SARL
 * @license     https://www.combodo.com/documentation/combodo-software-license.html
 */

/** @noinspection PhpUnhandledExceptionInspection */
SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-global-requests-mgmt/1.6.3',
	array(
		// Identification
		//
		'label'        => 'iTop Global Requests Management',
		'category'     => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-portal-base/3.2.0',
			'approval-base/2.5.1',
			'combodo-approval-extended/1.2.3',
			'itop-config-mgmt/3.2.0',
			'itop-tickets/3.2.0',
			'combodo-dispatch-userrequest/1.1.4',
			'itop-request-mgmt-itil/3.2.0||itop-request-mgmt/3.2.0',
			'itop-service-mgmt/3.2.0||itop-service-mgmt-provider/3.2.0',
			'itop-request-template/2.0.1',
			'itop-request-template-portal/1.0.0',
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => GlobalRequestInstaller::class,

		// Components
		//
		'datamodel' => array(
			'vendor/autoload.php',
			// Explicitly load hooks classes
			'src/Hook/GRPopupMenuExtension.php',
			// Explicitly load DM classes
			'model.itop-global-requests-mgmt.php',
			//Needed for symfony dependency injection
			'src/Portal/Router/GlobalRequestBrickRouter.php',
		),
		'webservice' => array(),
		'data.struct' => array(// add your 'structure' definition XML files here,
		),
		'data.sample' => array(// add your sample data XML files here,
		),

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => array(
			'target_state' => 'new',
			'bypass_profiles' => 'Administrator, Service Manager',
			'reuse_previous_answers' => true,
		),
	)
);


class GlobalRequestInstaller extends ModuleInstallerAPI
{
	/**
	 * Handler called before creating or upgrading the database schema
	 *
	 * @param $oConfiguration Config The new configuration of the application
	 * @param $sPreviousVersion string Previous version number of the module (empty string in case of first install)
	 * @param $sCurrentVersion string Current version number of the module
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
	{
		if (strlen($sPreviousVersion) > 0 && version_compare($sPreviousVersion, '1.6.0', '<')) {
			$slnkGRTypeToServiceSubcategory = MetaModel::DBGetTable('lnkGRTypeToServiceSubcategory','parent_servicesubcategory_id');
			$oAttDefToUpdate = MetaModel::GetAttributeDef('lnkGRTypeToServiceSubcategory', 'parent_servicesubcategory_id');
			$aColumnsToUpdate = array_keys($oAttDefToUpdate->GetSQLColumns());
			$sColumnToUpdate = $aColumnsToUpdate[0]; // We know that a string has only one column*/
			$oAttDefLink = MetaModel::GetAttributeDef('lnkGRTypeToServiceSubcategory', 'servicesubcategory_id');
			$aColumnsLink = array_keys($oAttDefLink->GetSQLColumns());
			$sColumnLink = $aColumnsLink[0]; // We know that a string has only one column*/

			$sTableToRead = MetaModel::DBGetTable('ServiceSubcategory', 'parent_servicesubcategory_id');
			$oAttDefToRead = MetaModel::GetAttributeDef('ServiceSubcategory', 'parent_servicesubcategory_id');
			$aColumnsToReads = array_keys($oAttDefToRead->GetSQLColumns());
			$sColumnToRead = $aColumnsToReads[0]; // We know that a string has only one column
			$sTableToReadPrimaryKey = MetaModel::DBGetKey('ServiceSubcategory');

			$sQueryUpdate = "
				UPDATE `$slnkGRTypeToServiceSubcategory` 
				JOIN `$sTableToRead` 
				ON `$slnkGRTypeToServiceSubcategory`.`$sColumnLink` = `$sTableToRead`.`$sTableToReadPrimaryKey` 
				SET `$slnkGRTypeToServiceSubcategory`.`$sColumnToUpdate` = `$sTableToRead`.`$sColumnToRead` 
				WHERE `$slnkGRTypeToServiceSubcategory`.`$sColumnToUpdate` = 0 OR `$slnkGRTypeToServiceSubcategory`.`$sColumnToUpdate` IS NULL
			";
            SetupLog::Info(" GlobalRequestInstaller Query: " . $sQueryUpdate);
			CMDBSource::Query($sQueryUpdate);
		}
	}
}



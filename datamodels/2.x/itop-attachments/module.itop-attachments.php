<?php
// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-attachments/3.2.0',
	array(
		// Identification
		//
		'label' => 'Tickets Attachments',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'AttachmentInstaller',

		// Components
		//
		'datamodel' => array(
			'vendor/autoload.php',
			'main.itop-attachments.php',
			'renderers.itop-attachments.php',
		),
		'webservice' => array(
			
		),
		'dictionary' => array(

		),
		'data.struct' => array(
			// add your 'structure' definition XML files here,
		),
		'data.sample' => array(
			// add your sample data XML files here,
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any 

		// Default settings
		//
		'settings' => array(
			'allowed_classes' => array('Ticket'), // List of classes for which to manage "Attachments"
			'position' => 'relations', // Where to display the attachments: relations | properties
			'preview_max_width' => 290,
			'icon_preview_max_size' => 500000, // Maximum size for attachment preview to be displayed as an icon. In bits
		),
	)
);

if (!class_exists('AttachmentInstaller'))
{
	// Module installation handler
	//
	class AttachmentInstaller extends ModuleInstallerAPI
	{
		public static function BeforeWritingConfig(Config $oConfiguration)
		{
			// If you want to override/force some configuration values, do it here
			return $oConfiguration;
		}

		/**
		 * @since 2.7.4 N°3788
		 * @param string $sTableName
		 * @param int $iBulkSize
		 *
		 * @return array
		 * @throws \CoreException
		 * @throws \MySQLException
		 * @throws \MySQLHasGoneAwayException
		 */
		public static function GetOrphanAttachmentIds($sTableName, $iBulkSize){
			$sSqlQuery = <<<SQL
SELECT id as attachment_id FROM `$sTableName` WHERE (`item_id`='' OR `item_id` IS NULL) LIMIT {$iBulkSize};
SQL;
			/** @var \mysqli_result $oQueryResult */
			$oQueryResult = CMDBSource::Query($sSqlQuery);

			$aIds = [];
			while($aRow = $oQueryResult->fetch_array()){
				$aIds[] = $aRow['attachment_id'];
			}

			return $aIds;
		}

		/**
		 * Handler called before creating or upgrading the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function BeforeDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			if ($sPreviousVersion != '')
			{
				// Migrating from a previous version
				// Check for records where item_id = '', since they are not attached to any object and cannot be migrated to the objkey schema
				$sTableName = MetaModel::DBGetTable('Attachment');
				$sCountQuery = "SELECT COUNT(*) FROM `$sTableName` WHERE (`item_id`='' OR `item_id` IS NULL)";
				$iCount = CMDBSource::QueryToScalar($sCountQuery);
				if ($iCount > 0)
				{
					SetupLog::Info("Cleanup of orphan attachments that cannot be migrated to the new ObjKey model: $iCount record(s) must be deleted.");

					$iBulkSize = 100;
					$iMaxDuration = 30;
					$iDeletedCount = 0;
					$fStartTime = microtime(true);
					$aIds = self::GetOrphanAttachmentIds($sTableName, $iBulkSize);

					while (count($aIds) !== 0) {
						$sCleanupQuery = sprintf("DELETE FROM `$sTableName` WHERE `id` IN (%s)", implode(",", $aIds));
						CMDBSource::Query($sCleanupQuery); // Throws an exception in case of error

						$iDeletedCount += count($aIds);
						$fElapsed = microtime(true) - $fStartTime;

						if ($fElapsed > $iMaxDuration){
							SetupLog::Info(sprintf("Cleanup of orphan attachments interrupted after %.3f s. $iDeletedCount records were deleted among $iCount.", $fElapsed));
							break;
						}

						$aIds = self::GetOrphanAttachmentIds($sTableName, $iBulkSize);
					}

					if (count($aIds) === 0){
						SetupLog::Info("Cleanup of orphan attachments successfully completed.");
					}
				}
				else
				{
					SetupLog::Info("No orphan attachment found.");
				}
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
			// For each record having item_org_id unset,
			//    get the org_id from the container object 
			//
			// Prerequisite: change null into 0 (workaround to the fact that we cannot use IS NULL in OQL)
			SetupLog::Info("Initializing attachment/item_org_id - null to zero");
			$sTableName = MetaModel::DBGetTable('Attachment');

			$sRepair = "UPDATE `$sTableName` SET `item_org_id` = 0 WHERE `item_org_id` IS NULL";
			CMDBSource::Query($sRepair);

			SetupLog::Info("Initializing attachment/item_org_id - zero to the container");
			$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_org_id = 0");
			$oSet = new DBObjectSet($oSearch);
			$oSet->OptimizeColumnLoad([
				'Attachment' => [
					'item_class',
					'item_id',
				]
			]);
			$iUpdated = 0;
			while ($oAttachment = $oSet->Fetch())
			{
				if (empty($oAttachment->Get('item_class'))) {
					//do not treat orphan attachment
					continue;
				}

				$oContainer = MetaModel::GetObject($oAttachment->Get('item_class'), $oAttachment->Get('item_id'), false /* must be found */, true /* allow all data */);

				if ($oContainer) {
					$oAttachment->SetItem($oContainer, true /*updateonchange*/);
					$iUpdated++;
				}
			}
			SetupLog::Info("Initializing attachment/item_org_id - $iUpdated records have been adjusted");

			if (MetaModel::GetAttributeDef('Attachment', 'contact_id') instanceof AttributeExternalKey) {
				SetupLog::Info("Upgrading itop-attachment from '$sPreviousVersion' to '$sCurrentVersion'. Starting with 3.2.0, contact_id will be added into the DB...");
				$sUserTableName = MetaModel::DBGetTable('User');
				$sUserFieldContactId = MetaModel::GetAttributeDef('User', 'contactid')->Get('sql');
				$sAttachmentFieldUserId = MetaModel::GetAttributeDef('Attachment', 'user_id')->Get('sql');
				$sAttachmentFieldContactId = MetaModel::GetAttributeDef('Attachment', 'contact_id')->Get('sql');
				$sAddContactId = "UPDATE `$sTableName` att, `$sUserTableName` us SET att.`$sAttachmentFieldContactId` = us.`$sUserFieldContactId` WHERE att.`$sAttachmentFieldUserId` = us.id AND att.`$sAttachmentFieldContactId` = 0";

				CMDBSource::Query($sAddContactId);
				$iNbProcessed = CMDBSource::AffectedRows();
				SetupLog::Info("|  | ".$iNbProcessed." attachment processed.");
			}
		}
	}
}

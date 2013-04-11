<?php
// Copyright (C) 2010-2012 Combodo SARL
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
	'itop-attachments/1.0.0',
	array(
		// Identification
		//
		'label' => 'Tickets attachments',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'AttachmentInstaller',

		// Components
		//
		'datamodel' => array(
			'model.itop-attachments.php',
			'main.attachments.php',
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
			// For each record having item_org_id unset,
			//    get the org_id from the container object 
			//
			// Prerequisite: change null into 0 (workaround to the fact that we cannot use IS NULL in OQL)
			SetupPage::log_info("Initializing attachment/item_org_id - null to zero"); 
			$sTableName = MetaModel::DBGetTable('Attachment');
			$sRepair = "UPDATE `$sTableName` SET `item_org_id` = 0 WHERE `item_org_id` IS NULL";
			CMDBSource::Query($sRepair);

			SetupPage::log_info("Initializing attachment/item_org_id - zero to the container");
			$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_org_id = 0");
			$oSet = new DBObjectSet($oSearch);
			$iUpdated = 0;
			while ($oAttachment = $oSet->Fetch())
			{
				$oContainer = MetaModel::GetObject($oAttachment->Get('item_class'), $oAttachment->Get('item_id'), false /* must be found */, true /* allow all data */);
				if ($oContainer)
				{
					$oAttachment->SetItem($oContainer, true /*updateonchange*/);
					$iUpdated++;
				}
			}

			SetupPage::log_info("Initializing attachment/item_org_id - $iUpdated records have been adjusted"); 
		}
	}
}

?>

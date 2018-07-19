<?php
// Copyright (C) 2012-2018 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'combodo-notify-on-expiration/0.1.0',
	array(
		// Identification
		//
		'label' => 'Notify on expiration',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(

		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'NotifyOnExpirationInstaller',

		// Components
		//
		'datamodel' => array(
			'model.combodo-notify-on-expiration.php',
			'main.combodo-notify-on-expiration.php',
			'triggerexpirationrule.class.inc.php',
		),
		'webservice' => array(
			
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
			// Module specific settings go here, if any
			'time' => '03:00',
			'enabled' => true,
			'debug' => false
		),
	)
);

if (!class_exists('NotifyOnExpirationInstaller'))
{
	// Module installation handler
	//
	class NotifyOnExpirationInstaller extends ModuleInstallerAPI
	{
		public static function BeforeWritingConfig(Config $oConfiguration)
		{
			return $oConfiguration;
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

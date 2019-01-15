<?php
/**
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     https://www.combodo.com/documentation/combodo-software-license.html
 *
 */

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

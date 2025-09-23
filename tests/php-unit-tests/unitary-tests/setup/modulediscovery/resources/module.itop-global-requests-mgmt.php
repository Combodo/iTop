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
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => GlobalRequestInstaller::class,

		// Components
		//
		'datamodel' => array(
			'vendor/autoload.php',
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
		//code
	}
}



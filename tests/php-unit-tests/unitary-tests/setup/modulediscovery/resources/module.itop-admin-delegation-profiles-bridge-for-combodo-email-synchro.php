<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'itop-admin-delegation-profiles-bridge-for-combodo-email-synchro/1.0.0',
	[
		// Identification
		//
		'label' => 'Profiles per admin fonction: Mail inboxes and messages',
		'category' => 'Datamodel',

		// Setup
		//
		'dependencies' => [
			'itop-admin-delegation-profiles/1.0.0',
			'itop-admin-delegation-profiles/1.0.0 || combodo-email-synchro/3.7.2 || itop-oauth-client/2.7.7', // Optional dependency to silence the setup to not display a warning if the other module is not present
		],
		'mandatory' => false,
		'visible' => false,
		'auto_select' => 'SetupInfo::ModuleIsSelected("itop-admin-delegation-profiles") && SetupInfo::ModuleIsSelected("combodo-email-synchro") && SetupInfo::ModuleIsSelected("itop-oauth-client")',

		// Components
		//
		'datamodel' => [
			'model.itop-admin-delegation-profiles-bridge-for-combodo-email-synchro.php',
		],
		'webservice' => [

		],
		'data.struct' => [
			// add your 'structure' definition XML files here,
		],
		'data.sample' => [
			// add your sample data XML files here,
		],

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => [
			// Module specific settings go here, if any
		],
	]
);

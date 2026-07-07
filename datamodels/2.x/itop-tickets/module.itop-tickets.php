<?php

SetupWebPage::AddModule(
	__FILE__,
	'itop-tickets/3.3.0',
	[
		// Identification
		//
		'label' => 'Tickets Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-structure/2.7.1 || itop-portal/3.0.0', // itop-portal : module_design_itop_design->module_designs->itop-portal
		],
		'mandatory' => false,
		'visible' => true,
		'installer' => 'TicketsInstaller',

		// Components
		//
		'datamodel' => [
			'main.itop-tickets.php',
		],
		'data.struct' => [
	//		'data.struct.ta-actions.xml',
		],
		'data.sample' => [
		],

		// Documentation
		//
		'doc.manual_setup'     => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => [
		],
	]
);

// Module installation handler
//
class TicketsInstaller extends ModuleInstallerAPI
{
	public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
	{
		// Delete all Triggers corresponding to a no more valid class
		CMDBObject::SetTrackInfo('Uninstallation');
		$oSearch = new DBObjectSearch('TriggerOnObject');
		$oSet = new DBObjectSet($oSearch);
		while ($oTrigger = $oSet->Fetch()) {
			try {
				if (!MetaModel::IsValidClass($oTrigger->Get('target_class'))) {
					$oTrigger->DBDelete();
				}
			} catch (Exception $e) {
				utils::EnrichRaisedException($oTrigger, $e);
			}
		}
		// Load localized structural data: predefined query phrases for notifications
		static::LoadLocalizedDataOnCrossingVersion($oConfiguration, $sPreviousVersion, $sCurrentVersion, '3.0.0', utils::GetAbsoluteModulePath('itop-tickets')."/data/data.itop-tickets.en_us.xml");
	}
}

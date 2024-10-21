<?php


SetupWebPage::AddModule(
	__FILE__,
	'itop-tickets/3.2.0',
	array(
		// Identification
		//
		'label' => 'Tickets Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-structure/2.7.1',
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'TicketsInstaller',

		// Components
		//
		'datamodel' => array(
			'main.itop-tickets.php',
		),
		'data.struct' => array(
	//		'data.struct.ta-actions.xml',
		),
		'data.sample' => array(
		),
		
		// Documentation
		//
		'doc.manual_setup'     => 'https://www.itophub.io/wiki/page?id='.utils::GetItopVersionWikiSyntax().':admin:cron',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
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
		while($oTrigger = $oSet->Fetch())
		{
			try
			{
				if (!MetaModel::IsValidClass($oTrigger->Get('target_class')))
				{
					$oTrigger->DBDelete();
				}
			}
			catch(Exception $e)
			{
				utils::EnrichRaisedException($oTrigger, $e);
			}
		}
		// It's not very clear if it make sense to test a particular version,
		// as the loading mechanism checks object existence using reconc_keys
		// and do not recreate them, nor update existing.
		// Without test, new entries added to the data files, would be automatically loaded
		if (($sPreviousVersion === '') ||
			(version_compare($sPreviousVersion, $sCurrentVersion, '<')
				&& version_compare($sPreviousVersion, '3.0.0', '<'))) {
			$oDataLoader = new XMLDataLoader();

			CMDBObject::SetTrackInfo("Initialization TicketsInstaller");
			$oMyChange = CMDBObject::GetCurrentChange();

			$sLang = null;
			// - Try to get app. language from configuration fil (app. upgrade)
			$sConfigFileName = APPCONF.'production/'.ITOP_CONFIG_FILE;
			if (file_exists($sConfigFileName)) {
				$oFileConfig = new Config($sConfigFileName);
				if (is_object($oFileConfig)) {
					$sLang = str_replace(' ', '_', strtolower($oFileConfig->GetDefaultLanguage()));
				}
			}

			// - I still no language, get the default one
			if (null === $sLang) {
				$sLang = str_replace(' ', '_', strtolower($oConfiguration->GetDefaultLanguage()));
			}

			$sFileName = dirname(__FILE__)."/data/{$sLang}.data.itop-tickets.xml";
			SetupLog::Info("Searching file: $sFileName");
			if (!file_exists($sFileName)) {
				$sFileName = dirname(__FILE__)."/data/en_us.data.itop-tickets.xml";
			}
			SetupLog::Info("Loading file: $sFileName");
			$oDataLoader->StartSession($oMyChange);
			$oDataLoader->LoadFile($sFileName, false, true);
			$oDataLoader->EndSession();
		}
	}
}

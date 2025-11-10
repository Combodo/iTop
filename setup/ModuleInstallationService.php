<?php

class ModuleInstallationService
{
	private static ModuleInstallationService $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): ModuleInstallationService
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new ModuleInstallationService();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?ModuleInstallationService $oInstance): void
	{
		static::$oInstance = $oInstance;
	}

	private ?array $aSelectInstall = null;
	public function ReadFromDB(?Config $oConfig): array
	{
		try {
			$aSelectInstall = [];
			if (! is_null($oConfig)) {
				if (! is_null($this->aSelectInstall)) {
					//test only
					$aSelectInstall = $this->aSelectInstall;
				} else {
					CMDBSource::InitFromConfig($oConfig);

					//read db module installations
					$aSelectInstallOld = CMDBSource::QueryToArray("SELECT * FROM ".$oConfig->Get('db_subname')."priv_module_install");
					//file_put_contents(APPROOT."/tests/php-unit-tests/unitary-tests/setup/ressources/priv_modules.json", json_encode($aSelectInstallOld, JSON_PRETTY_PRINT));

					$iRootId = CMDBSource::QueryToScalar("SELECT max(parent_id) FROM ".$oConfig->Get('db_subname')."priv_module_install");
					$sDbSubName = $oConfig->Get('db_subname');
					// Get the latest installed modules, without the "root" ones (iTop version and datamodel version)
					$sSQL = <<<SQL
SELECT * FROM $sDbSubName.priv_module_install 
WHERE 
parent_id='$iRootId'
OR id='$iRootId'
SQL;
					$aSelectInstall = CMDBSource::QueryToArray($sSQL);
					//file_put_contents(APPROOT."/tests/php-unit-tests/unitary-tests/setup/ressources/priv_modules2.json", json_encode($aSelectInstall, JSON_PRETTY_PRINT));
				}
			}
		} catch (MySQLException $e) {
			// No database or erroneous information
		}

		return $this->ComputeInstalledModules($aSelectInstall);
	}

	private function ComputeInstalledModulesLegacy(array $aSelectInstall): array
	{
		$aInstallByModule = []; // array of <module> => array ('installed' => timestamp, 'version' => <version>)
		$iRootId = 0;
		foreach ($aSelectInstall as $aInstall) {
			if (($aInstall['parent_id'] == 0) && ($aInstall['name'] != 'datamodel')) {
				// Root module, what is its ID ?
				$iId = (int) $aInstall['id'];
				if ($iId > $iRootId) {
					$iRootId = $iId;
				}
			}
		}

		foreach ($aSelectInstall as $aInstall) {
			//$aInstall['comment']; // unsused
			$iInstalled = strtotime($aInstall['installed']);
			$sModuleName = $aInstall['name'];
			$sModuleVersion = $aInstall['version'];
			if ($sModuleVersion == '') {
				// Though the version cannot be empty in iTop 2.0, it used to be possible
				// therefore we have to put something here or the module will not be considered
				// as being installed
				$sModuleVersion = '0.0.0';
			}

			if ($aInstall['parent_id'] == 0) {
				$sModuleName = ROOT_MODULE;
			} elseif ($aInstall['parent_id'] != $iRootId) {
				// Skip all modules belonging to previous installations
				continue;
			}

			if (array_key_exists($sModuleName, $aInstallByModule)) {
				if ($iInstalled < $aInstallByModule[$sModuleName]['installed']) {
					continue;
				}
			}

			if ($aInstall['parent_id'] == 0) {
				$aInstallByModule[$sModuleName]['installed_version'] = $sModuleVersion;
			}

			$aInstallByModule[$sModuleName]['installed'] = $iInstalled;
			$aInstallByModule[$sModuleName]['version'] = $sModuleVersion;
		}

		return $aInstallByModule;
	}

	private function ComputeInstalledModules(array $aSelectInstall): array
	{
		$aInstallByModule = []; // array of <module> => array ('installed' => timestamp, 'version' => <version>)

		//module installation datetime is mostly the same for all modules
		//unless there was issue recording things in DB
		$sFirstDatetime = null;
		$iFirstTime = -1;
		foreach ($aSelectInstall as $aInstall) {
			//$aInstall['comment']; // unsused
			$sDatetime = $aInstall['installed'];

			if (is_null($sFirstDatetime)) {
				$sFirstDatetime = $sDatetime;
				$iFirstTime = strtotime($sDatetime);
				$iInstalled = $iFirstTime;
			} elseif ($sDatetime === $sFirstDatetime) {
				$iInstalled = $iFirstTime;
			} else {
				$sDatetime = $aInstall['installed'];
				$iInstalled = strtotime($sDatetime);
			}

			$sModuleName = $aInstall['name'];
			$sModuleVersion = $aInstall['version'];
			if ($sModuleVersion == '') {
				// Though the version cannot be empty in iTop 2.0, it used to be possible
				// therefore we have to put something here or the module will not be considered
				// as being installed
				$sModuleVersion = '0.0.0';
			}

			if ($aInstall['parent_id'] == 0) {
				$aInstallByModule[ROOT_MODULE] = [
					'installed_version' => $sModuleVersion,
					'installed' => $iInstalled,
					'version' => $sModuleVersion,
				];
			} else {
				$aInstallByModule[$sModuleName] = [
					'installed' => $iInstalled,
					'version' => $sModuleVersion,
				];
			}
		}

		return $aInstallByModule;
	}
}

<?php

require_once __DIR__.'/ModuleInstallationService.php';

class AnalyzeInstallation
{
	private static AnalyzeInstallation $oInstance;
	private ?array $aAvailableModules = null;
	private ?array $aSelectInstall = null;

	protected function __construct()
	{
	}

	final public static function GetInstance(): AnalyzeInstallation
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new AnalyzeInstallation();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?AnalyzeInstallation $oInstance): void
	{
		static::$oInstance = $oInstance;
	}

	/**
	 * Analyzes the current installation and the possibilities
	 *
	 * @param null|Config $oConfig Defines the target environment (DB)
	 * @param mixed $modulesPath Either a single string or an array of absolute paths
	 * @param bool $bAbortOnMissingDependency ...
	 * @param array $aModulesToLoad List of modules to search for, defaults to all if omitted
	 *
	 * @return array Array with the following format:
	 * array =>
	 *     'iTop' => array(
	 *         'installed_version' => ... (could be empty in case of a fresh install)
	 *         'available_version => ...
	 *     )
	 *     <module_name> => array(
	 *         'installed_version' => ...
	 *         'available_version' => ...
	 *         'install' => array(
	 *             'flag' => SETUP_NEVER | SETUP_OPTIONAL | SETUP_MANDATORY
	 *             'message' => ...
	 *         )
	 *         'uninstall' => array(
	 *             'flag' => SETUP_NEVER | SETUP_OPTIONAL | SETUP_MANDATORY
	 *             'message' => ...
	 *         )
	 *         'label' => ...
	 *         'dependencies' => array(<module1>, <module2>, ...)
	 *         'visible' => true | false
	 *     )
	 * )
	 * @throws \Exception
	 */
	public function AnalyzeInstallation(?Config $oConfig, mixed $modulesPath, bool $bAbortOnMissingDependency = false, ?array $aModulesToLoad = null)
	{
		$aRes = [
			ROOT_MODULE => [
				'installed_version' => '',
				'available_version' => ITOP_VERSION_FULL,
				'name_code' => ITOP_APPLICATION,
			],
		];

		$aDirs = is_array($modulesPath) ? $modulesPath : [$modulesPath];
		if (! is_null($this->aAvailableModules)) {
			//test only
			$aAvailableModules = $this->aAvailableModules;
		} else {
			$aAvailableModules = ModuleDiscovery::GetAvailableModules($aDirs, $bAbortOnMissingDependency, $aModulesToLoad);
		}

		foreach ($aAvailableModules as $sModuleId => $aModuleInfo) {
			list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);

			$aModuleInfo['installed_version'] = '';
			$aModuleInfo['available_version'] = $sModuleVersion;

			if ($aModuleInfo['mandatory']) {
				$aModuleInfo['install'] = [
					'flag' => MODULE_ACTION_MANDATORY,
					'message' => 'the module is part of the application',
				];
			} else {
				$aModuleInfo['install'] = [
					'flag' => MODULE_ACTION_OPTIONAL,
					'message' => '',
				];
			}
			$aRes[$sModuleName] = $aModuleInfo;
		}

		$aCurrentlyInstalledModules = ModuleInstallationService::GetInstance()->ReadComputeInstalledModules($oConfig);

		// Adjust the list of proposed modules
		foreach ($aCurrentlyInstalledModules as $sModuleName => $aModuleDB) {
			if ($sModuleName == ROOT_MODULE) {
				$aRes[$sModuleName]['installed_version'] = $aModuleDB['version'];
				continue;
			}

			if (!array_key_exists($sModuleName, $aRes)) {
				// A module was installed, it is not proposed in the new build... skip
				continue;
			}

			$aRes[$sModuleName]['installed_version'] = $aModuleDB['version'];

			if ($aRes[$sModuleName]['mandatory']) {
				$aRes[$sModuleName]['uninstall'] = [
					'flag' => MODULE_ACTION_IMPOSSIBLE,
					'message' => 'the module is part of the application',
				];
			} else {
				$aRes[$sModuleName]['uninstall'] = [
					'flag' => MODULE_ACTION_OPTIONAL,
					'message' => '',
				];
			}
		}

		return $aRes;
	}
}

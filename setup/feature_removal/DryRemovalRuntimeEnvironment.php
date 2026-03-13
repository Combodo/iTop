<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use Combodo\iTop\Setup\ModuleDependency\Module;
use Config;
use InstallationChoicesToModuleConverter;
use iTopExtensionsMap;
use MetaModel;
use ModuleDiscovery;
use RunTimeEnvironment;
use SetupUtils;
use utils;

class DryRemovalRuntimeEnvironment extends RunTimeEnvironment
{
	protected array $aExtensionsByCode;

	/**
	 * Toolset for building a run-time environment
	 *
	 *  @param string $sSourceEnv: environment from which setup is inspired to simulate extension removal and usee CompileFrom...
	 */
	public function __construct($sSourceEnv = 'production', array $aExtensionCodesToRemove = [])
	{
		parent::__construct($sSourceEnv, false);
		$this->aExtensionsByCode = $aExtensionCodesToRemove;
		$this->Prepare($sSourceEnv, $this->sTargetEnv);
	}

	/**
	* @param string $sSourceEnv
	* @param string $sTargetEnv
	* @return void
	* @throws \MissingDependencyException
	 */
	private function Prepare(string $sSourceEnv, string $sTargetEnv)
	{
		$this->Cleanup();
		SetupUtils::copydir(APPROOT."/data/$sSourceEnv-modules", APPROOT."/data/$sTargetEnv-modules");
		SetupUtils::copydir(APPROOT."/conf/$sSourceEnv", APPROOT."/conf/$sTargetEnv");

		$this->DeclareExtensionAsRemoved($this->aExtensionsByCode);

		$sSourceDir = MetaModel::GetConfig()->Get('source_dir');
		$aSearchDirs = $this->GetExtraDirsToCompile($sSourceDir);

		$aModulesToLoad = $this->GetModulesToLoad($sSourceEnv, $aSearchDirs);

		try {
			ModuleDiscovery::GetModulesOrderedByDependencies($aSearchDirs, true, $aModulesToLoad);
		} catch (\MissingDependencyException $e) {
			\IssueLog::Error("Cannot prepare setup due to dependency issue", null, ['msg' => $e->getMessage(), 'modules_to_load' => $aModulesToLoad]);
			throw $e;
		}
	}

	private function DeclareExtensionAsRemoved(array $aExtensionCodes): void
	{
		$oExtensionsMap = new iTopExtensionsMap($this->sTargetEnv);
		$oExtensionsMap->DeclareExtensionAsRemoved($aExtensionCodes);
	}

	private function GetModulesToLoad(string $sSourceEnv, $aSearchDirs): array
	{
		$oSourceConfig = new Config(utils::GetConfigFilePath($sSourceEnv));
		$aChoices = iTopExtensionsMap::GetChoicesFromDatabase($oSourceConfig);
		$sSourceDir = $oSourceConfig->Get('source_dir');

		$sInstallFilePath = APPROOT.$sSourceDir.'/installation.xml';
		if (! is_file($sInstallFilePath)) {
			$sInstallFilePath = null;
		}

		$aModuleIdsToLoad = InstallationChoicesToModuleConverter::GetInstance()->GetModules($aChoices, $aSearchDirs, $sInstallFilePath);
		$aModulesToLoad = [];
		foreach ($aModuleIdsToLoad as $sModuleId) {
			$oModule = new Module($sModuleId);
			$sModuleName = $oModule->GetModuleName();
			$aModulesToLoad[] = $sModuleName;
		}
		return $aModulesToLoad;
	}

	public function Cleanup(): void
	{
		$sEnv = $this->sTargetEnv;

		//keep this folder empty
		SetupUtils::tidydir(APPROOT."/env-$sEnv");

		$aFolders = [
			APPROOT."/data/$sEnv-modules",
			APPROOT."/data/cache-$sEnv",
			APPROOT."/conf/$sEnv",
		];
		foreach ($aFolders as $sFolder) {
			SetupUtils::tidydir($sFolder);
			SetupUtils::rmdir_safe($sFolder);
		}

		$sFiles = [
			APPROOT."/data/datamodel-$sEnv.xml",
			APPROOT."/data/$sEnv.delta.prev.xml",
		];
		foreach ($sFiles as $sFile) {
			if (is_file($sFile)) {
				@unlink($sFile);
			}
		}
	}
}

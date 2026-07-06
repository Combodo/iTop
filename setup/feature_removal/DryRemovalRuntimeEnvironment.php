<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use Config;
use RunTimeEnvironment;
use SetupUtils;

class DryRemovalRuntimeEnvironment extends RunTimeEnvironment
{
	protected array $aExtensionsToRemoveByCode;
	protected array $aExtensionCodesToAddByCode;

	/**
	 * Toolset for building a run-time environment
	 *
	 *  @param string $sSourceEnv: environment from which setup is inspired to simulate extension removal and usee CompileFrom...
	 */
	public function __construct($sSourceEnv = ITOP_DEFAULT_ENV, array $aExtensionCodesToAdd = [], array $aExtensionCodesToRemove = [])
	{
		parent::__construct($sSourceEnv, false);
		$this->aExtensionCodesToAddByCode = $aExtensionCodesToAdd;
		$this->aExtensionsToRemoveByCode = $aExtensionCodesToRemove;
		$this->Prepare($sSourceEnv, $this->sBuildEnv);
	}

	/**
	 * Compile the data model by imitating the given environment
	 * The list of modules to be installed in the build environment is:
	 *  - the list of modules present in the "source_dir" (defined by the source environment) which are marked as "installed" in the source environment's database
	 *  - plus the list of modules present in the "extra" directory of the build environment: data/<build_environment>-modules/
	 *
	 * @param string $sSourceEnv The name of the source environment to 'imitate'
	 * @param null $bUseSymLinks Whether to create symbolic links instead of copies
	 *
	 * @return string[]
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function CompileFrom($sSourceEnv, $bUseSymLinks = null)
	{
		$aSelecteModules = $this->GetExtensionMap()->GetSelectedModules();
		$this->DoCompile($this->aExtensionCodesToAddByCode, $this->aExtensionsToRemoveByCode, $this->GetExtensionMap()->GetSelectedModules(), $bUseSymLinks ?? false);
		return $aSelecteModules;
	}



	/**
	* @param string $sSourceEnv
	* @param string $sBuildEnv
	* @return void
	* @throws \MissingDependencyException
	 */
	private function Prepare(string $sSourceEnv, string $sBuildEnv)
	{
		$this->Cleanup();
		SetupUtils::copydir(APPROOT."/data/$sSourceEnv-modules", APPROOT."/data/$sBuildEnv-modules");
		SetupUtils::copydir(APPROOT."/conf/$sSourceEnv", APPROOT."/conf/$sBuildEnv");

		$oSourceConfig = new Config(APPCONF.$sSourceEnv.'/'.ITOP_CONFIG_FILE);
		$sSourceDir = $oSourceConfig->Get('source_dir');
		list($aExtraDirs, ) = $this->GetDirsToCompile($sSourceDir, $sSourceEnv);

		$this->InitExtensionMap($aExtraDirs, $oSourceConfig);
		$this->GetExtensionMap()->DeclareExtensionAsRemoved($this->aExtensionsToRemoveByCode);

		foreach ($this->GetExtensionMap()->GetAllExtensions() as $oExtension) {
			if (array_key_exists($oExtension->sCode, $this->aExtensionCodesToAddByCode)) {
				$oExtension->MarkAsChosen();
			}
		}
	}

	public function Cleanup(): void
	{
		$sEnv = $this->sBuildEnv;

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

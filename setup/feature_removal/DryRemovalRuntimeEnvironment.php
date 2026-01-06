<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use iTopExtensionsMap;
use MetaModel;
use RunTimeEnvironment;
use SetupUtils;

class DryRemovalRuntimeEnvironment extends RunTimeEnvironment
{
	public const DRY_REMOVAL_AUDIT_ENV = "extension-removal";

	protected array $aExtensionsByCode;

	/**
	 * Toolset for building a run-time environment
	 *
	 * @param string $sEnvironment (e.g. 'test')
	 * @param bool $bAutoCommit (make the target environment directly, or build a temporary one)
	 */
	public function __construct($sEnvironment = self::DRY_REMOVAL_AUDIT_ENV, $bAutoCommit = true)
	{
		parent::__construct($sEnvironment, $bAutoCommit);
		$this->aExtensionsByCode = [];
	}

	/**
	 * @param string $sSourceEnv
	 * @param array $aExtensionCodesToRemove
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function Prepare(string $sSourceEnv, array $aExtensionCodesToRemove)
	{

		$sEnv = $this->sFinalEnv;
		$this->aExtensionsByCode = $aExtensionCodesToRemove;
		//SetupUtils::rrmdir(APPROOT."/data/$sEnv-modules");
		$this->Cleanup();
		SetupUtils::copydir(APPROOT."/data/$sSourceEnv-modules", APPROOT."/data/$sEnv-modules");

		$this->DeclareExtensionAsRemoved($aExtensionCodesToRemove);
		$oDryRemovalConfig = clone(MetaModel::GetConfig());
		$oDryRemovalConfig->ChangeModulesPath($sSourceEnv, $this->sFinalEnv);
		$this->WriteConfigFileSafe($oDryRemovalConfig);
	}

	private function DeclareExtensionAsRemoved(array $aExtensionCodes): void
	{
		$oExtensionsMap = new iTopExtensionsMap($this->sFinalEnv);
		$oExtensionsMap->DeclareExtensionAsRemoved($aExtensionCodes);
	}

	public function Cleanup()
	{
		$sEnv = $this->sFinalEnv;
		SetupUtils::rrmdir(APPROOT."/data/$sEnv-modules");
		SetupUtils::rrmdir(APPROOT."/data/cache-$sEnv");
		SetupUtils::rrmdir(APPROOT."/env-$sEnv");
		SetupUtils::rrmdir(APPROOT."/conf/$sEnv");
		@unlink(APPROOT."/data/datamodel-$sEnv.xml");
	}
}

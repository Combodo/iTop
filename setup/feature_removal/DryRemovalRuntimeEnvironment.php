<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use MetaModel;
use RunTimeEnvironment;
use SetupUtils;

class DryRemovalRuntimeEnvironment extends RunTimeEnvironment
{
	public const DRY_REMOVAL_AUDIT_ENV = "extension-removal";

	protected array $aExtensionsByCode;
	private bool $bExtensionMapModified = false;

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

		if (count($aExtensionCodesToRemove) > 0) {
			$this->RemoveExtensionsLocally($aExtensionCodesToRemove);
		}
		$oDryRemovalConfig = clone(MetaModel::GetConfig());
		$oDryRemovalConfig->ChangeModulesPath($sSourceEnv, $this->sFinalEnv);
		$this->WriteConfigFileSafe($oDryRemovalConfig);
	}

	private function RemoveExtensionsLocally(array $aExtensionCodes): void
	{
		$oExtensionsMap = new \iTopExtensionsMap($this->sFinalEnv);

		foreach ($aExtensionCodes as $sCode) {
			/** @var \iTopExtension $oExtension */
			$oExtension = $oExtensionsMap->Get($sCode);
			if (!is_null($oExtension)) {
				$sDir = $oExtension->sSourceDir;
				\IssueLog::Info(__METHOD__.": remove extension locally", null, [$oExtension->sCode => $sDir]);
				SetupUtils::rrmdir($sDir);
			} else {
				\IssueLog::Warning(__METHOD__." cannot find extensions", null, ['env' => $this->sFinalEnv, 'code' => $sCode]);
			}
		}
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

	/**
	 * @return \iTopExtensionsMap|null
	 */
	/*protected function GetExtensionMap(): ?iTopExtensionsMap
	{
		if (is_null(parent::GetExtensionMap())) {
			return null;
		}

		if (!$this->bExtensionMapModified) {
			$this->bExtensionMapModified = true;
			foreach ($this->aExtensionsByCode as $sCode) {
				parent::GetExtensionMap()->RemoveExtension($sCode);
			}
		}

		return parent::GetExtensionMap();
	}*/
}

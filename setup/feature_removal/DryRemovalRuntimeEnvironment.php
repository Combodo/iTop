<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use iTopExtensionsMap;
use RunTimeEnvironment;
use SetupUtils;

class DryRemovalRuntimeEnvironment extends RunTimeEnvironment
{
	const DRY_REMOVAL_AUDIT_ENV = "extension-removal";

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
		SetupUtils::rrmdir(APPROOT."/data/$sEnv-modules");
		SetupUtils::copydir(APPROOT."/data/$sSourceEnv-modules", APPROOT."/data/".$this->sFinalEnv."-modules");

		/*$oDryRemovalConfig = clone(MetaModel::GetConfig());
		$oDryRemovalConfig->ChangeModulesPath($sSourceEnv, $this->sFinalEnv);
		$this->WriteConfigFileSafe($oDryRemovalConfig);*/
	}

	/**
	 * @return \iTopExtensionsMap|null
	 */
	protected function GetExtensionMap(): ?iTopExtensionsMap
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
	}
}
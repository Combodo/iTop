<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use Config;
use CoreException;
use DBObjectSearch;
use DBObjectSet;
use Exception;
use MetaModel;
use RunTimeEnvironment;
use SetupUtils;

class SetupAudit
{
	const DRY_REMOVAL_AUDIT_ENV = "extension-removal";

	private array $aClassesBeforeRemoval;
	private array $aClassesAfterRemoval;
	private array $aExtensionToRemove;
	private array $aRemovedClasses;
	private array $aFinalClassesRemoved;

	public function __construct()
	{
		$this->aExtensionToRemove = [];
		$this->aClassesBeforeRemoval = [];
		$this->aClassesAfterRemoval = [];
		$this->aRemovedClasses = [];
		$this->aFinalClassesRemoved = [];
	}

	public function SetSelectedExtensions(Config $oConfig, array $aSelectedExtensions)
	{
		$oExtensionsMap = new \iTopExtensionsMap();
		$oExtensionsMap->LoadChoicesFromDatabase($oConfig);

		sort($aSelectedExtensions);
		$this->aExtensionToRemove = $oExtensionsMap->GetMissingExtensions($aSelectedExtensions);
		sort($this->aExtensionToRemove);
		\SetupLog::Info(__METHOD__, null, ['aExtensionToRemove' => $this->aExtensionToRemove]);
	}

	public function ComputeClassesBeforeRemoval(string $sTargetEnv)
	{
		$this->aClassesBeforeRemoval = $this->GetModelFromEnvironment($sTargetEnv);
	}

	public function SetClassesAfterRemovalFromCurrentEnv()
	{
		$this->aClassesAfterRemoval = MetaModel::GetClasses();
	}

	public function SetClassesBeforeRemovalFromCurrentEnv()
	{
		$this->aClassesBeforeRemoval = MetaModel::GetClasses();
	}

	public function ComputeDryExtensionRemoval(array $aExtensionToRemove): void
	{
		$this->aExtensionToRemove = $aExtensionToRemove;

		if (count($this->aExtensionToRemove) == 0) {
			//avoid time consuming setup audit when no extension removed
			return;
		}

		$sDryRemovalEnv = self::DRY_REMOVAL_AUDIT_ENV;
		self::Cleanup($sDryRemovalEnv);

		$sSourceEnvt = MetaModel::GetEnvironment();

		$oDryRemovalRuntimeEnvt = new RunTimeEnvironment($sDryRemovalEnv);
		$oDryRemovalConfig = clone(MetaModel::GetConfig());
		$oDryRemovalConfig->ChangeModulesPath($sSourceEnvt, $sDryRemovalEnv);

		$oDryRemovalRuntimeEnvt->WriteConfigFileSafe($oDryRemovalConfig);
		SetupUtils::copydir(APPROOT."/data/$sSourceEnvt-modules", APPROOT."/data/$sDryRemovalEnv-modules");
		$this->RemoveExtensionsLocally($sDryRemovalEnv, $this->aExtensionToRemove);

		$oDryRemovalRuntimeEnvt->CompileFrom($sSourceEnvt);

		$this->aClassesAfterRemoval = $this->GetModelFromEnvironment($sDryRemovalEnv);

		$oDryRemovalRuntimeEnvt->Rollback();
		self::Cleanup($sDryRemovalEnv);
	}

	public static function Cleanup(string $sEnv)
	{
		SetupUtils::rrmdir(APPROOT."/data/$sEnv-modules");
		SetupUtils::rrmdir(APPROOT."/data/cache-$sEnv");
		SetupUtils::rrmdir(APPROOT."/env-$sEnv");
		SetupUtils::rrmdir(APPROOT."/conf/$sEnv");
		@unlink(APPROOT."/data/datamodel-$sEnv.xml");
	}

	public function GetModelFromEnvironment(string $sEnv): array
	{
		$sPHPExec = trim(\MetaModel::GetConfig()->Get('php_path'));
		$sOutput = "";
		$iRes = 0;
		exec(sprintf("$sPHPExec %s/get_model_reflection.php --env='%s'", __DIR__, $sEnv), $sOutput, $iRes);
		if ($iRes != 0) {
			\IssueLog::Error("Cannot get classes", null, ['code' => $iRes, "output" => $sOutput]);
			throw new CoreException("Cannot get classes");
		}

		$aClasses = json_decode($sOutput[0] ?? null, true);
		if (false === $aClasses) {
			\IssueLog::Error("Invalid JSON", null, ["output" => $sOutput]);
			throw new Exception("cannot get classes");
		}

		if (!is_array($aClasses)) {
			\IssueLog::Error("not an array", null, ["classes" => $aClasses]);
			throw new Exception("cannot get classes");
		}

		return $aClasses;
	}

	private function RemoveExtensionsLocally(string $sTargetEnv, array $aExtensionCodes): void
	{
		$oExtensionsMap = new \iTopExtensionsMap($sTargetEnv);

		foreach ($aExtensionCodes as $sCode) {
			/** @var \iTopExtension $oExtension */
			$oExtension = $oExtensionsMap->Get($sCode);
			if (!is_null($oExtension)) {
				$sDir = $oExtension->sSourceDir;
				\IssueLog::Info(__METHOD__.": remove extension locally", null, [$oExtension->sCode => $sDir]);
				SetupUtils::rrmdir($sDir);
			} else {
				\IssueLog::Warning(__METHOD__." cannot find extensions", null, ['env' => $sTargetEnv, 'code' => $sCode]);
			}
		}
	}

	public function GetRemovedClasses(): array
	{
		if (count($this->aRemovedClasses) == 0) {
			if (count($this->aClassesBeforeRemoval) == 0) {
				return $this->aRemovedClasses;
			}

			if (count($this->aClassesAfterRemoval) == 0) {
				return $this->aRemovedClasses;
			}

			$aExtensionsNames = array_diff($this->aClassesBeforeRemoval, $this->aClassesAfterRemoval);
			$this->aRemovedClasses = [];
			$aClasses = array_values($aExtensionsNames);
			sort($aClasses);

			foreach ($aClasses as $i => $sClass) {
				$this->aRemovedClasses[] = $sClass;
			}
		}

		return $this->aRemovedClasses;
	}

	public function AuditExtensionsCleanupRules(bool $bStopAtFirstIssue = false): array
	{
		$this->aFinalClassesRemoved = [];

		foreach ($this->GetRemovedClasses() as $sClass) {
			if (MetaModel::IsAbstract($sClass)) {
				continue;
			}

			if (!MetaModel::IsStandaloneClass($sClass)) {
				$iCount = $this->Count($sClass);
				$this->aFinalClassesRemoved[$sClass] = $iCount;
				if ($bStopAtFirstIssue && $iCount > 0) {
					//setup envt: should raise issue ASAP
					throw new \Exception($sClass);
				}
			}
		}

		return $this->aFinalClassesRemoved;
	}

	private function Count($sClass): int
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT $sClass", []);
		$oSearch->AllowAllData();
		$oSet = new DBObjectSet($oSearch);

		return $oSet->Count();
	}
}
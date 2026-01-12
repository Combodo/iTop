<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use ContextTag;
use DBObjectSearch;
use DBObjectSet;
use IssueLog;
use MetaModel;
use SetupLog;

require_once APPROOT.'setup/feature_removal/ModelReflectionSerializer.php';

abstract class AbstractSetupAudit
{
	//file used when present to trigger audit exception when testing specific setups
	public const GETISSUE_ERROR_MSG_FILE_FORTESTONLY = '.setup_audit_error_msg.txt';

	protected bool $bClassesInitialized = false;
	protected array $aClassesBeforeRemoval = [];
	protected array $aClassesAfterRemoval = [];
	protected array $aRemovedClasses = [];
	protected array $aFinalClassesToCleanup = [];

	public function __construct()
	{
	}

	abstract public function ComputeClasses(): void;

	public function GetRemovedClasses(): array
	{
		$this->ComputeClasses();

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

	/** test only: return file path that force audit error being raised
	 *
	 * @return string
	 */
	public static function GetErrorMessageFilePathForTestOnly(): string
	{
		return APPROOT."/data/".self::GETISSUE_ERROR_MSG_FILE_FORTESTONLY;
	}

	public function GetIssues(bool $bStopDataCheckAtFirstIssue = false): array
	{
		$sErrorMessageFilePath = self::GetErrorMessageFilePathForTestOnly();
		if ($bStopDataCheckAtFirstIssue && is_file($sErrorMessageFilePath)) {
			$sMsg = file_get_contents($sErrorMessageFilePath);
			throw new \Exception($sMsg);
		}

		$this->aFinalClassesToCleanup = [];

		foreach ($this->GetRemovedClasses() as $sClass) {
			if (MetaModel::IsAbstract($sClass)) {
				continue;
			}

			if (!MetaModel::IsStandaloneClass($sClass)) {
				$iCount = $this->Count($sClass);
				$this->aFinalClassesToCleanup[$sClass] = $iCount;
				if ($bStopDataCheckAtFirstIssue && $iCount > 0) {
					//setup envt: should raise issue ASAP
					$this->LogInfoWithProperLogger("Setup audit found data to cleanup", null, $this->aFinalClassesToCleanup);
					return $this->aFinalClassesToCleanup;
				}
			}
		}

		$this->LogInfoWithProperLogger("Setup audit found data to cleanup", null, ['data_to_cleanup' => $this->aFinalClassesToCleanup]);
		return $this->aFinalClassesToCleanup;
	}

	public function GetDataToCleanupCount(): int
	{
		$res = 0;
		foreach ($this->aFinalClassesToCleanup as $sClass => $iCount) {
			$res += $iCount;
		}
		return $res;
	}

	private function Count($sClass): int
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT $sClass", []);
		$oSearch->AllowAllData();
		$oSet = new DBObjectSet($oSearch);

		return $oSet->Count();
	}

	//could be shared with others in log APIs ?
	private function LogInfoWithProperLogger($sMessage, $sChannel = null, $aContext = []): void
	{
		if (ContextTag::Check(ContextTag::TAG_SETUP)) {
			SetupLog::Info($sMessage, $sChannel, $aContext);
		} else {
			IssueLog::Info($sMessage, $sChannel, $aContext);
		}
	}
}

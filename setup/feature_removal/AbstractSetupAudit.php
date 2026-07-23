<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use ContextTag;
use DBObjectSearch;
use DBObjectSet;
use IssueLog;
use MetaModel;
use SetupLog;
use TagSetFieldData;

require_once APPROOT.'setup/feature_removal/ModelReflectionSerializer.php';

abstract class AbstractSetupAudit
{
	protected bool $bClassesInitialized = false;
	protected array $aClassesBefore = [];
	protected array $aClassesAfter = [];
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
			if (count($this->aClassesBefore) == 0) {
				return $this->aRemovedClasses;
			}

			if (count($this->aClassesAfter) == 0) {
				return $this->aRemovedClasses;
			}

			$aExtensionsNames = array_diff($this->aClassesBefore, $this->aClassesAfter);
			$this->aRemovedClasses = [];
			$aClasses = array_values($aExtensionsNames);
			sort($aClasses);

			foreach ($aClasses as $i => $sClass) {
				$this->aRemovedClasses[] = $sClass;
			}
		}

		return $this->aRemovedClasses;
	}

	/**
	 * Generate issues when audit detects data to remove
	 *
	 * @param bool $bStopDataCheckAtFirstIssue
	 *
	 * @return array
	 * @throws \CoreException
	 */
	public function RunDataAudit(bool $bStopDataCheckAtFirstIssue = false): array
	{
		$this->aFinalClassesToCleanup = [];

		foreach ($this->GetRemovedClasses() as $sClass) {
			if (MetaModel::IsAbstract($sClass)) {
				continue;
			}

			if (MetaModel::IsSameFamily($sClass, TagSetFieldData::class)) {
				continue;
			}

			if (!MetaModel::IsStandaloneClass($sClass)) {
				$iCount = $this->Count($sClass);
				$this->aFinalClassesToCleanup[$sClass] = $iCount;
				if ($bStopDataCheckAtFirstIssue && $iCount > 0) {
					//setup env: should raise issue ASAP
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
			IssueLog::Debug($sMessage, $sChannel, $aContext);
		}
	}
}

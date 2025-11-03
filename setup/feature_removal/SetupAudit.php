<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use DBObjectSearch;
use DBObjectSet;
use MetaModel;

require_once APPROOT.'setup/feature_removal/ModelReflectionSerializer.php';

class SetupAudit
{
	//file used when present to trigger audit exception when testing specific setups
	const GETISSUE_ERROR_MSG_FILE_FORTESTONLY = '.setup_audit_error_msg.txt';

	private string $sEnvBeforeExtensionRemoval;
	private string $sEnvAfterExtensionRemoval;

	private array $aClassesBeforeRemoval;
	private array $aClassesAfterRemoval;
	private array $aRemovedClasses;
	private array $aFinalClassesRemoved;

	public function __construct(string $sEnvBeforeExtensionRemoval, string $sEnvAfterExtensionRemoval = DryRemovalRuntimeEnvironment::DRY_REMOVAL_AUDIT_ENV)
	{
		$this->sEnvBeforeExtensionRemoval = $sEnvBeforeExtensionRemoval;
		$this->sEnvAfterExtensionRemoval = $sEnvAfterExtensionRemoval;

		$sCurrentEnvt = MetaModel::GetEnvironment();
		if ($sCurrentEnvt === $this->sEnvBeforeExtensionRemoval) {
			$this->aClassesBeforeRemoval = MetaModel::GetClasses();
		} else {
			$this->aClassesBeforeRemoval = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->sEnvBeforeExtensionRemoval);
		}

		if ($sCurrentEnvt === $this->sEnvAfterExtensionRemoval) {
			$this->aClassesAfterRemoval = MetaModel::GetClasses();
		} else {
			$this->aClassesAfterRemoval = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->sEnvAfterExtensionRemoval);
		}

		$this->aRemovedClasses = [];
		$this->aFinalClassesRemoved = [];
	}

	/*public function SetSelectedExtensions(Config $oConfig, array $aSelectedExtensions)
	{
		$oExtensionsMap = new \iTopExtensionsMap();
		$oExtensionsMap->LoadChoicesFromDatabase($oConfig);

		sort($aSelectedExtensions);
		$this->aExtensionToRemove = $oExtensionsMap->GetMissingExtensions($aSelectedExtensions);
		sort($this->aExtensionToRemove);
		\SetupLog::Info(__METHOD__, null, ['aExtensionToRemove' => $this->aExtensionToRemove]);
	}*/

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

	/** test only: return file path that force audit error being raised
	 *
	 * @return string
	 */
	public static function GetErrorMessageFilePathForTestOnly(): string
	{
		return APPROOT."/data/".self::GETISSUE_ERROR_MSG_FILE_FORTESTONLY;
	}

	public function GetIssues(bool $bThrowExceptionAtFirstIssue = false): array
	{
		$sErrorMessageFilePath = self::GetErrorMessageFilePathForTestOnly();
		if ($bThrowExceptionAtFirstIssue && is_file($sErrorMessageFilePath)) {
			$sMsg = file_get_contents($sErrorMessageFilePath);
			throw new \Exception($sMsg);
		}

		$this->aFinalClassesRemoved = [];

		foreach ($this->GetRemovedClasses() as $sClass) {
			if (MetaModel::IsAbstract($sClass)) {
				continue;
			}

			if (!MetaModel::IsStandaloneClass($sClass)) {
				$iCount = $this->Count($sClass);
				$this->aFinalClassesRemoved[$sClass] = $iCount;
				if ($bThrowExceptionAtFirstIssue && $iCount > 0) {
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
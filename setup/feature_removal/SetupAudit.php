<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use MetaModel;

require_once __DIR__.'/AbstractSetupAudit.php';
require_once APPROOT.'setup/feature_removal/ModelReflectionSerializer.php';

class SetupAudit extends AbstractSetupAudit
{
	//file used when present to trigger audit exception when testing specific setups
	public const GETISSUE_ERROR_MSG_FILE_FORTESTONLY = '.setup_audit_error_msg.txt';

	private string $sEnvBeforeExtensionRemoval;
	private string $sEnvAfterExtensionRemoval;

	public function __construct(string $sEnvBeforeExtensionRemoval, string $sEnvAfterExtensionRemoval)
	{
		parent::__construct();
		$this->sEnvBeforeExtensionRemoval = $sEnvBeforeExtensionRemoval;
		$this->sEnvAfterExtensionRemoval = $sEnvAfterExtensionRemoval;
	}

	public function ComputeClasses(): void
	{
		if ($this->bClassesInitialized) {
			return;
		}

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

		$this->bClassesInitialized = true;
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
}

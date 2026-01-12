<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use MetaModel;

require_once __DIR__.'/AbstractSetupAudit.php';
require_once APPROOT.'setup/feature_removal/ModelReflectionSerializer.php';

class InplaceSetupAudit extends AbstractSetupAudit
{
	//file used when present to trigger audit exception when testing specific setups
	public const GETISSUE_ERROR_MSG_FILE_FORTESTONLY = '.setup_audit_error_msg.txt';

	private string $sEnvAfterExtensionRemoval;

	public function __construct(array $aClassesBeforeRemoval, string $sEnvAfterExtensionRemoval)
	{
		parent::__construct();
		$this->aClassesBeforeRemoval = $aClassesBeforeRemoval;
		$this->sEnvAfterExtensionRemoval = $sEnvAfterExtensionRemoval;
	}

	public function ComputeClasses(): void
	{
		if ($this->bClassesInitialized) {
			return;
		}

		$sCurrentEnvt = MetaModel::GetEnvironment();

		if ($sCurrentEnvt === $this->sEnvAfterExtensionRemoval) {
			$this->aClassesAfterRemoval = MetaModel::GetClasses();
		} else {
			$this->aClassesAfterRemoval = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->sEnvAfterExtensionRemoval);
		}

		$this->bClassesInitialized = true;
	}
}

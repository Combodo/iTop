<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use MetaModel;

require_once __DIR__.'/AbstractSetupAudit.php';
require_once APPROOT.'setup/feature_removal/ModelReflectionSerializer.php';

class InplaceSetupAudit extends AbstractSetupAudit
{
	//file used when present to trigger audit exception when testing specific setups
	public const GETISSUE_ERROR_MSG_FILE_FORTESTONLY = '.setup_audit_error_msg.txt';

	private string $sEnvAfter;

	public function __construct(array $aClassesBefore, string $sEnvAfter)
	{
		parent::__construct();
		$this->aClassesBefore = $aClassesBefore;
		$this->sEnvAfter = $sEnvAfter;
	}

	public function ComputeClasses(): void
	{
		if ($this->bClassesInitialized) {
			return;
		}

		$sCurrentEnvt = MetaModel::GetEnvironment();

		if ($sCurrentEnvt === $this->sEnvAfter) {
			$this->aClassesAfter = MetaModel::GetClasses();
		} else {
			$this->aClassesAfter = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->sEnvAfter);
		}

		$this->bClassesInitialized = true;
	}
}

<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

require_once __DIR__.'/AbstractSetupAudit.php';
require_once APPROOT.'setup/feature_removal/ModelReflectionSerializer.php';

class SetupAudit extends AbstractSetupAudit
{
	private string $sEnvBefore;
	private string $sEnvAfter;

	public function __construct(string $sEnvBefore)
	{
		parent::__construct();
		$this->sEnvBefore = $sEnvBefore;
		$this->sEnvAfter = "$sEnvBefore-build";
	}

	public function ComputeClasses(): void
	{
		if ($this->bClassesInitialized) {
			return;
		}

		$this->aClassesBefore = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->sEnvBefore);
		$this->aClassesAfter = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->sEnvAfter);

		$this->bClassesInitialized = true;
	}

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
}

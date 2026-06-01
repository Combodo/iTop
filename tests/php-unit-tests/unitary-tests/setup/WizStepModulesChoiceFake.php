<?php

class WizStepModulesChoiceFake extends WizStepModulesChoice
{
	public function __construct(WizardController $oWizard, $sCurrentState)
	{
		$this->oWizard = $oWizard;
		$this->sCurrentState = $sCurrentState;
	}

	public function setExtensionMap(iTopExtensionsMap $oMap)
	{
		$this->oExtensionsMap = $oMap;
	}
}

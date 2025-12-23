<?php

class WizStepModulesChoiceFake extends WizStepModulesChoice
{
	public function __construct(WizardController $oWizard, $sCurrentState)
	{

	}

	public function setExtensionMap(iTopExtensionsMap $oMap)
	{
		$this->oExtensionsMap = $oMap;
	}
}

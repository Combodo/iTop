<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class WizStepLandingBeforeAudit extends WizardStep
{
	/**
	 * @inheritDoc
	 */
	public function Display(SetupPage $oPage): void
	{
	}

	/**
	 * @inheritDoc
	 */
	public function UpdateWizardStateAndGetNextStep(bool $bMoveForward = true): WizardState
	{
		return new WizardState(WizStepDataAudit::class);
	}

	/**
	 * @inheritDoc
	 */
	public function GetTitle()
	{
		return 'Before checking compatibility';
	}

	public function GetPossibleSteps()
	{
		return [WizStepDataAudit::class];
	}

	public function GetNextButtonLabel()
	{
		return 'Next';
	}

	public function CanComeBack()
	{
		return false;
	}
}

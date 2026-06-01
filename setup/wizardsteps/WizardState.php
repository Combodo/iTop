<?php

class WizardState
{
	public function __construct(string $sNextStep, string $sCurrentState = '')
	{
		$this->sNextStep = $sNextStep;
		$this->sCurrentState = $sCurrentState;
	}
	private string $sNextStep;
	private string $sCurrentState;

	public function GetNextStep()
	{
		return $this->sNextStep;
	}
	public function GetState()
	{
		return $this->sCurrentState;
	}
}

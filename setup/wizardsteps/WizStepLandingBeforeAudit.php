<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class WizStepLandingBeforeAudit extends WizStepModulesChoice
{
	private RunTimeEnvironment $oRuntimeEnv;

	public function __construct(WizardController $oWizard, $sCurrentState)
	{
		$this->oRuntimeEnv = new RunTimeEnvironment($oWizard->GetParameter('target_env'));
		$sBuildConfigFile = APPCONF.$this->oRuntimeEnv->GetBuildEnv().'/'.ITOP_CONFIG_FILE;
		$this->oConfig = new Config($sBuildConfigFile);

		$oWizard->SetParameter('previous_version_dir', APPROOT);
		$oWizard->SetParameter('install_mode', 'upgrade');
		$oWizard->SetParameter('source_dir', APPROOT.$this->oConfig->Get('source_dir'));
		$oWizard->SetParameter('graphviz_path', $this->oConfig->Get('graphviz_path'));
		$oWizard->SetParameter('application_url', $this->oConfig->Get('app_root_url'));
		$oWizard->SetParameter('datamodel_version', ITOP_CORE_VERSION);
		$oWizard->SetParameter('upgrade_type', 'use-compatible');

		// should be done at the end
		parent::__construct($oWizard, $sCurrentState, false);
	}

	/**
	 * @inheritDoc
	 */
	public function Display(SetupPage $oPage): void
	{
	}

	/**
	 * @inheritDoc
	 */
	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		// Change the rights to production config file !
		$sBuildConfigFile = APPCONF.ITOP_DEFAULT_ENV.'/'.ITOP_CONFIG_FILE;
		@chmod($sBuildConfigFile, 0770); // In case it exists: RWX for owner and group, nothing for others

		$oConfig = new Config($sBuildConfigFile);
		$this->oWizard->SetParameter('db_server', $oConfig->Get('db_host'));
		$this->oWizard->SetParameter('db_user', $oConfig->Get('db_user'));
		$this->oWizard->SetParameter('db_pwd', $oConfig->Get('db_pwd'));
		$this->oWizard->SetParameter('db_name', $oConfig->Get('db_name'));
		$this->oWizard->SetParameter('db_prefix', $oConfig->Get('db_subname'));
		$this->oWizard->SetParameter('db_tls_enabled', $oConfig->Get('db_tls.enabled'));
		$this->oWizard->SetParameter('db_tls_ca', $oConfig->Get('db_tls.ca') ?? '');

		$this->oWizard->SetParameter('display_choices', '[]');
		$this->oWizard->SetParameter('extensions_not_uninstallable', '[]');

		$aWizardSteps = $this->GetWizardSteps();
		$this->oWizard->SetWizardSteps($aWizardSteps);
		$this->sCurrentState = count($aWizardSteps) - 1;

		$aSelectedComponents = $this->GetSelectedComponents($this->aSteps, $this->oWizard->GetParameter('selected_extensions'));
		$this->oWizard->SetParameter('selected_components', json_encode($aSelectedComponents));

		return new WizardState(WizStepDataAudit::class);
	}

	/**
	 * @inheritDoc
	 */
	public function GetTitle(): string
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

	public function CanMoveBackward()
	{
		return false;
	}
}

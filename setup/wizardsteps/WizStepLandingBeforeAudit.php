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

		$oWizard->SetParameter('db_server', $this->oConfig->Get('db_host'));
		$oWizard->SetParameter('db_user', $this->oConfig->Get('db_user'));
		$oWizard->SetParameter('db_pwd', $this->oConfig->Get('db_pwd'));
		$oWizard->SetParameter('db_name', $this->oConfig->Get('db_name'));
		$oWizard->SetParameter('db_prefix', $this->oConfig->Get('db_subname'));
		$oWizard->SetParameter('db_tls_enabled', $this->oConfig->Get('db_tls.enabled'));
		$oWizard->SetParameter('db_tls_ca', $this->oConfig->Get('db_tls.ca') ?? '');
		$oWizard->SetParameter('display_choices', '');
		$oWizard->SetParameter('extensions_not_uninstallable', '[]');

		$oWizard->SaveParameter('use_symbolic_links', MFCompiler::UseSymbolicLinks());
		$oWizard->SaveParameter('force-uninstall', '');

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

		$aWizardSteps = $this->GetWizardSteps();
		$this->oWizard->SetWizardSteps($aWizardSteps);
		$this->sCurrentState = count($aWizardSteps) - 1;

		$aSelectedComponents = $this->GetSelectedComponents($this->aSteps, $this->oWizard->GetParameter('selected_extensions'));
		$this->oWizard->SetParameter('selected_components', json_encode($aSelectedComponents));

		// Save the choices for the summary step
		$sDisplayChoices = '<ul>';
		$i = 0;
		foreach ($this->aSteps as $aStepInfo) {
			$sDisplayChoices .= $this->GetSelectedModules($aStepInfo, $aSelectedComponents[$i], $aModules, '', '', $aExtensions);
			$i++;
		}
		$sDisplayChoices .= '</ul>';
		$this->oWizard->SetParameter('display_choices', $sDisplayChoices);

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

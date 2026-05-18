<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class WizStepLandingBeforeAudit extends WizStepModulesChoice
{
	public function __construct(WizardController $oWizard, $sCurrentState)
	{
		$oProductionEnv = new RunTimeEnvironment();
		$sBuildConfigFile = APPCONF.$oProductionEnv->GetBuildEnv().'/'.ITOP_CONFIG_FILE;
		$this->oConfig = new Config($sBuildConfigFile);

		$oWizard->SetParameter('previous_version_dir', APPROOT);
		$oWizard->SetParameter('install_mode', 'upgrade');
		$oWizard->SetParameter('source_dir', APPROOT.$this->oConfig->Get('source_dir'));
		$oWizard->SetParameter('graphviz_path', $this->oConfig->Get('graphviz_path'));
		$oWizard->SetParameter('application_url', $this->oConfig->Get('app_root_url'));
		$oWizard->SetParameter('datamodel_version', ITOP_CORE_VERSION);
		$oWizard->SetParameter('upgrade_type', 'use-compatible');

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
		$this->oWizard->SetParameter('selected_components', '[{"_0":"_0","_1":"_1","_2":"_2","_3":"_3","_4":"_4"},{"_0":"_0"},{"_0":"_0","_0_0":"_0_0"},{"_0":"_0"},{"_0":"_0","_1":"_1"},{"_0":"_0","_1":"_1"}]');
		$aSteps = json_decode(
			'[
				{"class":"WizStepWelcome","state":""},
				{"class":"WizStepInstallOrUpgrade","state":""},
				{"class":"WizStepDetectedInfo","state":""},
				{"class":"WizStepUpgradeMiscParams","state":""},
				{"class":"WizStepModulesChoice","state":"start_upgrade"},
				{"class":"WizStepModulesChoice","state":"1"},
				{"class":"WizStepModulesChoice","state":"2"},
				{"class":"WizStepModulesChoice","state":"3"}]',
			true
		);
		$this->oWizard->SetSteps($aSteps);
		$this->aSteps = $aSteps;

		$this->sCurrentState = count($aSteps) - 1;
		//parent::UpdateWizardStateAndGetNextStep(true);

		$oProductionEnv = new RunTimeEnvironment();
		$sBuildConfigFile = APPCONF.$oProductionEnv->GetBuildEnv().'/'.ITOP_CONFIG_FILE;
		@chmod($sBuildConfigFile, 0770); // In case it exists: RWX for owner and group, nothing for others

		$oConfig = new Config($sBuildConfigFile);
		$this->oWizard->SetParameter('db_server', $oConfig->Get('db_host'));
		$this->oWizard->SetParameter('db_user', $oConfig->Get('db_user'));
		$this->oWizard->SetParameter('db_pwd', $oConfig->Get('db_pwd'));
		$this->oWizard->SetParameter('db_name', $oConfig->Get('db_name'));
		$this->oWizard->SetParameter('db_prefix', $oConfig->Get('db_subname'));
		$this->oWizard->SetParameter('db_tls_enabled', $oConfig->Get('db_tls.enabled'));
		$this->oWizard->SetParameter('db_tls_ca', $oConfig->Get('db_tls.ca') ?? '');

		$this->oWizard->SaveParameter('selected_modules', []);
		$this->oWizard->SaveParameter('selected_extensions', []);
		$this->oWizard->SaveParameter('display_choices', []);
		$this->oWizard->SaveParameter('added_extensions', []);
		$this->oWizard->SaveParameter('removed_extensions', []);
		$this->oWizard->SaveParameter('extensions_not_uninstallable', []);

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

	public function CanComeBack()
	{
		return false;
	}
}
